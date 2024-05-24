<?php

namespace Modules\Purchase\Helpers;

use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use Illuminate\Support\Str;
use Modules\Factcolombia1\Helpers\HttpConnectionApi;
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceCompany
};
use App\Models\Tenant\{
    Person
};
use Exception;
use Modules\Purchase\Models\{
    SupportDocument
};

class SupportDocumentHelper
{
    const REGISTERED = 1;
    const ACCEPTED = 5;
    const REJECTED = 6;

    private $company;

    public function __construct()
    {
        $this->company = ServiceCompany::select('api_token', 'type_environment_id')->firstOrFail();
    }

    /**
     * Retorna arreglo con data lista para insertar en payroll
     *
     * @param  mixed $inputs
     * @return array
     */
    public function getInputs($inputs)
    {
        $establishment_id = auth()->user()->establishment_id;
        $establishment = EstablishmentInput::set($establishment_id);
        $number = $this->getNumber($inputs->resolution_code, $inputs->prefix);

        $values = [
            'number' => $number,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => $establishment_id,
            'establishment' => $establishment,
            'supplier' => Person::with('typePerson', 'typeRegime', 'identity_document_type', 'country', 'department', 'city')->findOrFail($inputs->supplier_id),
            'state_document_id' => self::REGISTERED, //estado inicial
            'type_environment_id' => $this->company->type_environment_id,
            'adjust_note' => $this->getDataAdjustNote($inputs),
        ];

        return $inputs->merge($values)->all();
    }


    /**
     * Datos adicionales para nota de ajuste
     *
     * @param  array $inputs
     * @return array
     */
    public function getDataAdjustNote($inputs)
    {
        if($inputs->resolution_code === '13')
        {
            return [
                'affected_support_document_id' => $inputs->affected_support_document_id,
                'note_concept_id' => $inputs->note_concept_id,
                'discrepancy_response_description' => $inputs->discrepancy_response_description,
            ];
        }

        return null;
    }


    /**
     * Enviar documento a api
     *
     * @param  SupportDocument $support_document
     * @param  array $inputs
     * @return array
     */
    public function sendToApi($support_document, $inputs)
    {
        $connection_api = new HttpConnectionApi($this->company->api_token);

        $params = $this->getParamsForApi($support_document, $inputs);
        $url = $support_document->isAdjustNote() ? 'ubl2.1/sd-credit-note' : "ubl2.1/support-document";
        if($support_document->isAdjustNote())
                $invoice_lines = $params['credit_note_lines'];
        else
                $invoice_lines = $params['invoice_lines'];
        $new_invoice_lines = array();
        foreach($invoice_lines as $invoice_line){
                $tax = [['tax_id' => 1, 'tax_amount' => '0.00', 'percent' => '0', 'taxable_amount' => $invoice_line['line_extension_amount']]];
                $invoice_line['tax_totals'] = $tax;
                array_push($new_invoice_lines, $invoice_line);
        }
        if($support_document->isAdjustNote())
                $params['credit_note_lines'] = $new_invoice_lines;
        else
                $params['invoice_lines'] = $new_invoice_lines;

        $send_request_to_api = $connection_api->sendRequestToApi($url, $params, 'POST');
        // dd($send_request_to_api);

        //error validacion form request api
        if(isset($send_request_to_api['errors']))
        {
            $message = $connection_api->parseErrorsToString($send_request_to_api['errors']);
            $this->throwException($message);
        }

        // validacion respuesta api
        $this->validateResponseApi($send_request_to_api, $support_document->number_full, $connection_api, $support_document);

        return $send_request_to_api;
    }


    /**
     *
     * Validar response de la dian
     *
     * @param  array $send_request_to_api
     * @param  string $number_full
     * @param  HttpConnectionApi $connection_api
     * @param  SupportDocument $support_document
     * @return void
     */
    private function validateResponseApi($send_request_to_api, $number_full, HttpConnectionApi $connection_api, SupportDocument $support_document)
    {
        //parsear respuesta y verificar
        $send_bill_sync_result = $send_request_to_api['ResponseDian']['Envelope']['Body']['SendBillSyncResponse']['SendBillSyncResult'] ?? null;

        if(!$send_bill_sync_result) $this->throwException('Error inesperado: No se pudo parsear respuesta de la DIAN');


        if($send_bill_sync_result['IsValid'] == 'true')
        {
            //estado aceptado en produccion
            $this->updateStateDocument(self::ACCEPTED, $support_document);
        }
        else
        {
            // estado rechazado
            $extract_error_response = $send_bill_sync_result['ErrorMessage']['string'] ?? $send_bill_sync_result['StatusDescription'];
            $error_message_response = is_array($extract_error_response) ?  implode(",", $extract_error_response) : $extract_error_response;
            $this->throwException("Error al Validar Documento de soporte Nro: {$number_full} Errores: {$error_message_response}");
        }
    }


    /**
     * Actualizar estado del documento soporte
     *
     * @param  int $state_document_id
     * @param  SupportDocument $support_document
     * @return void
     */
    public function updateStateDocument($state_document_id, SupportDocument $support_document)
    {
        $support_document->update([
            'state_document_id' => $state_document_id
        ]);
    }


    /**
     *
     * Parametros para la api
     *
     * @param  SupportDocument $support_document
     * @param  array $inputs
     * @return array
     */
    public function getParamsForApi($support_document, $inputs)
    {
        $form_api = $inputs['data_api'];
        $form_api['number'] = $support_document->number;
        $form_api['seller']['dv'] = $this->validarDigVerifDIAN($form_api['seller']['identification_number']);
        $form_api['seller']['postal_zone_code'] = '111611';

        if($support_document->isAdjustNote())
        {
            $affected_document = $support_document->support_document_adjust_note->affected_support_document;

            $form_api['billing_reference'] = [
                'number' => $affected_document->prefix.$affected_document->number,
                'uuid' => $affected_document->getCuds(),
                'issue_date' => $affected_document->date_of_issue->format('Y-m-d')
            ];
        }

        return $form_api;
    }


    /**
     *
     * @param  string $message
     * @return void
     */
    public function throwException($message)
    {
        throw new Exception($message);
    }


    /**
     * Obtener correlativo desde el api
     *
     * @param  mixed $type_service
     * @param  mixed $ignore_state_document_id
     * @param  mixed $prefix
     */
    public function getNumber($type_service, $prefix)
    {
        $connection_api = new HttpConnectionApi($this->company->api_token);
        $url = "ubl2.1/invoice/current_number/{$type_service}/{$prefix}";

        $send_request_to_api = $connection_api->get($url);

        if(isset($send_request_to_api['success']))
        {
            return $send_request_to_api['number'];
        }

        return null;
    }

    protected function validarDigVerifDIAN($nit)
    {
        if(is_numeric(trim($nit))){
            $secuencia = array(3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71);
            $d = str_split(trim($nit));
            krsort($d);
            $cont = 0;
            unset($val);
            foreach ($d as $key => $value) {
                $val[$cont] = $value * $secuencia[$cont];
                $cont++;
            }
            $suma = array_sum($val);
            $div = intval($suma / 11);
            $num = $div * 11;
            $resta = $suma - $num;
            if ($resta == 1)
                return $resta;
            else
                if($resta != 0)
                    return 11 - $resta;
                else
                    return $resta;
        } else {
            return FALSE;
        }
    }
}
