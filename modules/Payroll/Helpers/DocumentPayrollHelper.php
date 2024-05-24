<?php

namespace Modules\Payroll\Helpers;

use App\CoreFacturalo\Requests\Inputs\Common\EstablishmentInput;
use Illuminate\Support\Str;
use Modules\Factcolombia1\Helpers\HttpConnectionApi;
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceCompany
};
use Modules\Payroll\Models\{
    DocumentPayroll,
    DocumentPayrollAdjustNote
};
use Exception;

class DocumentPayrollHelper
{

    const REGISTERED = 1;
    const ACCEPTED = 5;
    const REJECTED = 6;

    private $company;

    public function __construct()
    {
        $this->company = ServiceCompany::select('test_set_id_payroll', 'api_token', 'payroll_type_environment_id')->firstOrFail();
    }
    
    /**
     * Retorna arreglo con data lista para insertar en payroll
     *
     * @param  mixed $inputs
     * @return array
     */
    public function getInputs($inputs)
    {
        $establishment = EstablishmentInput::set(auth()->user()->establishment_id);
        $worker = WorkerInput::set($inputs->worker_id);

        $ignore_state_document_id = (int) ($this->company->payroll_type_environment_id === 2);
        $consecutive = $this->getConsecutive(9, $ignore_state_document_id, $inputs->prefix);

        $values = [
            'consecutive' => $consecutive,
            'user_id' => auth()->id(),
            'external_id' => Str::uuid()->toString(),
            'establishment_id' => auth()->user()->establishment_id,
            'establishment' => $establishment,
            'date_of_issue' => date('Y-m-d'),
            'time_of_issue' => date('H:i:s'),
            'worker' => $worker,
            'state_document_id' => self::REGISTERED, //estado inicial
            'payroll_type_environment_id' => $this->company->payroll_type_environment_id,
        ];
 
        return $inputs->merge($values)->all();
    }

    
    /**
     * Retorna arreglo con data para registro en nomina de eliminacion
     *
     * @param  mixed $inputs
     * @return array
     */
    public function getInputsAdjustNote($inputs)
    {
        $values = $this->getValuesAdjustNote($inputs);
        // dd($values);
 
        return $inputs->merge($values)->all();
    }
    

    /**
     * Obetener arreglo con data dependiendo del tipo (eliminar/reemplazar)
     *
     * @param $inputs
     * @return array
     */
    public function getValuesAdjustNote($inputs)
    {

        $establishment_id = auth()->user()->establishment_id;
        $establishment = EstablishmentInput::set($establishment_id);
        $ignore_state_document_id = (int) ($this->company->payroll_type_environment_id === 2);
        $consecutive = $this->getConsecutive(DocumentPayroll::ADJUST_NOTE_TYPE_DOCUMENT_ID, $ignore_state_document_id, $inputs->prefix);
        $affected_document_payroll_id = $inputs->document_payroll_id;

        $user_id = auth()->id();
        $external_id = Str::uuid()->toString();
        $date_of_issue = date('Y-m-d');
        $time_of_issue = date('H:i:s');

        $adjust_note = [
            'type_payroll_adjust_note_id' => $inputs->type_payroll_adjust_note_id,
            'affected_document_payroll_id' => $affected_document_payroll_id,
        ];

        // eliminar
        if($inputs->type_payroll_adjust_note_id === DocumentPayrollAdjustNote::ADJUST_NOTE_ELIMINATION_ID)
        {

            $document_payroll = DocumentPayroll::select('period', 'payroll_period_id', 'worker_id', 'worker')->findOrFail($affected_document_payroll_id);

            return [
                'consecutive' => $consecutive,
                'user_id' => $user_id,
                'external_id' => $external_id,
                'establishment_id' => $establishment_id,
                'establishment' => $establishment,
                'date_of_issue' => $date_of_issue,
                'time_of_issue' => $time_of_issue,
                'state_document_id' => self::REGISTERED, //estado inicial
                'payroll_type_environment_id' => $this->company->payroll_type_environment_id,
                'period' => $document_payroll->period,
                'payroll_period_id' => $document_payroll->payroll_period_id,
                'worker_id' => $document_payroll->worker_id,
                'worker' => $document_payroll->worker,
                'adjust_note' => $adjust_note,
            ];
        }


        //nómina reemplazo
        $worker = WorkerInput::set($inputs->worker_id);

        return [
            'consecutive' => $consecutive,
            'user_id' => $user_id,
            'external_id' => $external_id,
            'establishment_id' => $establishment_id,
            'establishment' => $establishment,
            'date_of_issue' => $date_of_issue,
            'time_of_issue' => $time_of_issue,
            'worker' => $worker,
            'state_document_id' => self::REGISTERED, //estado inicial
            'payroll_type_environment_id' => $this->company->payroll_type_environment_id,
            'adjust_note' => $adjust_note,
        ];
        
    }

    
    /**
     * 
     * Obtener url y data para enviar nómina a api
     *
     * @param  DocumentPayroll $document
     * @param  array $inputs
     */
    private function getEndpointDataApi(DocumentPayroll $document, $inputs)
    {
        if($document->is_payroll_adjust_note)
        {
            $params = $this->getParamsAdjustNoteForApi($document, $inputs);
            $url = ($this->company->payroll_type_environment_id == 2) ? "ubl2.1/payroll-adjust-note/{$this->company->test_set_id_payroll}" : "ubl2.1/payroll-adjust-note";
        }
        else
        {
            $params = $this->getParamsForApi($document, $inputs);
            $url = ($this->company->payroll_type_environment_id == 2) ? "ubl2.1/payroll/{$this->company->test_set_id_payroll}" : "ubl2.1/payroll";
        }

        return [
            'params' => $params,
            'url' => $url,
        ];
    }


    /**
     * Enviar nomina a la api
     *
     * @param  DocumentPayroll $document
     * @param  array $inputs
     */
    public function sendToApi(DocumentPayroll $document, $inputs)
    {

        $connection_api = new HttpConnectionApi($this->company->api_token);
        
        $data_api = $this->getEndpointDataApi($document, $inputs);
        $params = $data_api['params'];
        $url = $data_api['url'];
        // dd($url, $params);
        
        $send_request_to_api = $connection_api->sendRequestToApi($url, $params, 'POST');

        //error validacion form request api
        if(isset($send_request_to_api['errors']))
        {
            $message = $connection_api->parseErrorsToString($send_request_to_api['errors']);
            $this->throwException($message);
        }

        // validacion respuesta api entorno Pruebas/Produccion
        $this->validateResponseApi($send_request_to_api, $document->number_full, $connection_api, $document);

        return $send_request_to_api;

    }
    

    /**
     * Validar respuesta al enviar nomina, entorno prueba/produccion
     *
     * @param $send_request_to_api
     * @param $number_full
     * @param HttpConnectionApi $connection_api
     * @param DocumentPayroll $document
     * @return void
     */
    private function validateResponseApi($send_request_to_api, $number_full, HttpConnectionApi $connection_api, DocumentPayroll $document)
    {

        //entorno pruebas/habilitacion
        if($this->company->payroll_type_environment_id == 2){

            $zip_key = null;
            // dd($send_request_to_api);
    
            if(array_key_exists('urlpayrollpdf', $send_request_to_api) && array_key_exists('urlpayrollxml', $send_request_to_api))
            {
                
                //error desconocido - certificado
                $send_test_set_async_response = $send_request_to_api['ResponseDian']['Envelope']['Body']['SendTestSetAsyncResponse'] ?? null;

                if(is_null($send_test_set_async_response))
                {
                    $unknown_error = $send_request_to_api['ResponseDian']['Envelope']['Body']['Fault']['Reason']['Text']['_value'] ?? null;
                    if(!is_null($unknown_error)) $this->throwException('Error desconocido: '.$unknown_error);
                }
                //error desconocido - certificado

                $send_test_set_async_result = $send_test_set_async_response['SendTestSetAsyncResult'];
                $zip_key = $send_test_set_async_result['ZipKey'];
    
                if(!is_string($zip_key))
                {
                    if(is_string($send_test_set_async_result['ErrorMessageList']['XmlParamsResponseTrackId']['Success']))
                    {
                        if($send_test_set_async_result['ErrorMessageList']['XmlParamsResponseTrackId']['Success'] == 'false')
                        {
                            $this->throwException($send_test_set_async_result['ErrorMessageList']['XmlParamsResponseTrackId']['ProcessedMessage']);
                        }
                    }
                }
            }
            
            // $this->validateZipKey($zip_key, $number_full, $connection_api);

        }
        //entorno produccion
        else{

            //TODO parsear respuesta y verificar
            $send_bill_sync_result = $send_request_to_api['ResponseDian']['Envelope']['Body']['SendNominaSyncResponse']['SendNominaSyncResult'];

            if($send_bill_sync_result['IsValid'] == "true")
            {
                //estado aceptado en produccion, deberia actualizar un campo en bd para mostrar el mensaje directo de la dian
                $this->updateStateDocument(self::ACCEPTED, $document);
            }
            else
            {
                // estado rechazado
                $extract_error_response = $send_bill_sync_result['ErrorMessage']['string'] ?? $send_bill_sync_result['StatusDescription'];
                $error_message_response = is_array($extract_error_response) ?  implode(",", $extract_error_response) : $extract_error_response;
                $this->throwException("Error al Validar Nómina Nro: {$number_full} Errores: {$error_message_response}");

            }

        }

    }
    

    /**
     * 
     * Realiza peticion a ubl2.1/status/zip/{$zip_key} para validar el estado de la nomina (entorno pruebas/habilitacion)
     *
     * @param  string $zip_key
     * @param $number_full
     * @param HttpConnectionApi $connection_api
     * @param DocumentPayroll $document
     * @return void
     */
    public function validateZipKey($zip_key, $number_full, DocumentPayroll $document, HttpConnectionApi $connection_api = null)
    {

        if(!$connection_api){
            $connection_api = new HttpConnectionApi($this->company->api_token);
        }
        
        if($zip_key)
        {
            //esperar 3 segundos para ejecutar servicio status
            // sleep(3);

            $params_zip_key = [
                'is_payroll' => true
            ];

            $query_zip_key = $connection_api->sendRequestToApi("ubl2.1/status/zip/{$zip_key}", $params_zip_key, 'POST');
            // dd($query_zip_key);

            if(isset($query_zip_key['ResponseDian'])){

                $dian_response = $query_zip_key['ResponseDian']['Envelope']['Body']['GetStatusZipResponse']['GetStatusZipResult']['DianResponse'];

                if($dian_response['IsValid'] == "true")
                {

                    $message_zip_key = "{$dian_response['StatusCode']} - {$dian_response['StatusDescription']} - {$dian_response['StatusMessage']}";
                    $this->updateStateDocument(self::ACCEPTED, $document);
                    $this->updateMessageQueryZipkey($message_zip_key, $document);

                    return $this->responseMessage(true, $message_zip_key);

                }
                else
                {

                    // estado rechazado
                    $extract_error_zip_key = $dian_response['ErrorMessage']['string'] ?? $dian_response['StatusDescription'];
                    $error_message_zip_key = is_array($extract_error_zip_key) ?  implode(",", $extract_error_zip_key) : $extract_error_zip_key;

                    //excepcion
                    $status_code = $dian_response['StatusCode'] ?? [];
                    
                    // 'Batch en proceso de validación.'
                    if(empty($status_code)){
                        $this->throwException("Error al Validar Nómina Nro: {$number_full} Errores: {$error_message_zip_key}");
                    }

                    $this->updateStateDocument(self::REJECTED, $document);
                    $this->updateMessageQueryZipkey($error_message_zip_key, $document);
                    return $this->responseMessage(false, "Error al Validar Nómina Nro: {$number_full} Errores: {$error_message_zip_key}");

                }
            }
            else{
                $this->throwException("Error al Validar Nómina Nro: {$number_full} Errores: {$query_zip_key['message']}");
            }

        }
        else
        {
            $this->throwException('Error de ZipKey.');
        }

    }
    
    
    /**
     * 
     * Actualizar mensaje de respuesta al consultar zipkey de la nómina
     *
     * @param  string $response_message_query_zipkey
     * @param  DocumentPayroll $document
     * @return void
     */
    public function updateMessageQueryZipkey($response_message_query_zipkey, DocumentPayroll $document)
    {
        $document->update([
            'response_message_query_zipkey' => $response_message_query_zipkey
        ]);
    }

    /**
     * 
     * Actualizar estado de la nómina dependiendo de la validación al enviar a la dian
     *
     * @param  int $state_document_id
     * @param  DocumentPayroll $document
     * @return void
     */
    public function updateStateDocument($state_document_id, DocumentPayroll $document)
    {
        $document->update([
            'state_document_id' => $state_document_id
        ]);
    }


    /**
     * 
     * Obtener array para enviar a la api (nómina eliminación y reemplazo)
     *
     * @param $document
     * @param $inputs
     * @return array
     */
    public function getParamsAdjustNoteForApi($document, $inputs)
    {

        $type_document_id = (int) $document->type_document->code;
        $adjust_note = $document->adjust_note;
        $affected_document_payroll = $adjust_note->affected_document_payroll;
        $type_note = $adjust_note->type_payroll_adjust_note_id;

        $predecessor = [
            'predecessor_number'=> $affected_document_payroll->consecutive,
            'predecessor_cune'=> $affected_document_payroll->response_api->cune,
            'predecessor_issue_date'=> $affected_document_payroll->date_of_issue->format('Y-m-d')
        ];

        // dd($predecessor);

        // nóminas de eliminación
        if($adjust_note->is_adjust_note_elimination)
        {
            return [

                'type_document_id' => $type_document_id, //id tipo documento nomina eliminación
                'resolution_number' => $inputs['resolution_number'],
                'head_note' => $document->head_note,
                'foot_note' => $document->foot_note,
                'type_note'=> $type_note,
                'predecessor'=> $predecessor,
                'period' => $document->period,
                'prefix' => $document->prefix,
                'consecutive' => $document->consecutive,
                'payroll_period_id' => $document->payroll_period_id,
                'notes' => $document->notes,
            ];
        }

        // nóminas de ajuste
        $general_inputs = $this->getParamsForApi($document, $inputs);

        $adjust_note_replace_inputs = [
            'type_note'=> $type_note,
            'predecessor'=> $predecessor,
            'head_note' => $document->head_note,
            'foot_note' => $document->foot_note,
            'notes' => $document->notes,
        ];

        // dd($general_inputs, $adjust_note_replace_inputs ,array_merge($adjust_note_replace_inputs, $general_inputs));

        return array_merge($adjust_note_replace_inputs, $general_inputs);

    }
    
    /**
     * 
     * Obtener array para enviar a la api
     * Usado para enviar nomina inicial y de ajuste (reemplazo)
     * 
     * @param  mixed $document
     * @param  mixed $inputs
     * @return array
     */
    public function getParamsForApi($document, $inputs)
    {

        // $establishment = $document->establishment;
        $worker = $document->worker;
        $accrued = $document->accrued;
        $deduction = $document->deduction;
        $type_document_id = (int) $document->type_document->code; // se usa el id del tipo de documento (api), no de la resolucion (type_documents)
        // dd($inputs);

        return [
            'type_document_id' => $type_document_id, //id tipo documento nomina
            // 'type_document_id' => 9, //id tipo documento nomina
            'resolution_number' => $inputs['resolution_number'],
            // 'establishment_name' => $establishment->description,
            // 'establishment_address' => $establishment->address,
            // 'establishment_phone' => $establishment->telephone,
            // 'establishment_municipality' => null,
            // 'establishment_email' => $establishment->email,
            'head_note' => $document->head_note,
            'foot_note' => $document->foot_note,
            'novelty' => $document->novelty ?? [
                'novelty' => false,
                'uuidnov' => ''
            ],
            'period' => $document->period,
            'worker_code' => $worker->code,
            'prefix' => $document->prefix,
            'consecutive' => $document->consecutive,
            'payroll_period_id' => $document->payroll_period_id,
            'notes' => $document->notes,
            'worker' => [
                'type_worker_id' => $worker->type_worker_id,
                'sub_type_worker_id' => $worker->sub_type_worker_id,
                'payroll_type_document_identification_id' => $worker->payroll_type_document_identification_id,
                'municipality_id' => $worker->municipality_id,
                'type_contract_id' => $worker->type_contract_id,
                'high_risk_pension' => $worker->high_risk_pension,
                'identification_number' => $worker->identification_number,
                'surname' => $worker->surname,
                'second_surname' => $worker->second_surname,
                'first_name' => $worker->first_name,
                'address' => $worker->address,
                'integral_salarary' => $worker->integral_salarary,
                'salary' => $worker->salary,
            ],
            'payment' => $document->payment,
            'payment_dates' => $document->payment_dates, 
            'accrued' => [
                'worked_days' => $accrued->worked_days,
                'salary' => $accrued->salary,
                'accrued_total' => $accrued->accrued_total,
                'transportation_allowance' => $accrued->transportation_allowance,
                'telecommuting' => $accrued->telecommuting,
                'endowment' => $accrued->endowment,
                'salary_viatics' => $accrued->salary_viatics,
                'non_salary_viatics' => $accrued->non_salary_viatics,
                'refund' => $accrued->refund,
                
                'sustenance_support' => $accrued->sustenance_support,
                'withdrawal_bonus' => $accrued->withdrawal_bonus,
                'compensation' => $accrued->compensation,
                'work_disabilities' => $accrued->parseWorkDisabilitiesToFormatApi($accrued->work_disabilities),
                'service_bonus' => $accrued->service_bonus,
                'severance' => $accrued->severance,
                'bonuses' => $accrued->bonuses,
                'aid' => $accrued->aid,
                'other_concepts' => $accrued->other_concepts,
                'common_vacation' => $accrued->parseCommonVacationToFormatApi($accrued->common_vacation),
                'paid_vacation' => $accrued->parsePaidVacationToFormatApi($accrued->paid_vacation),
                'maternity_leave' => $accrued->parseLicensesToFormatApi($accrued->maternity_leave, 'maternity'),
                'paid_leave' => $accrued->parseLicensesToFormatApi($accrued->paid_leave, 'paid'),
                'non_paid_leave' => $accrued->parseLicensesToFormatApi($accrued->non_paid_leave, 'non_paid'),
                'commissions' => $accrued->commissions,
                'epctv_bonuses' => $accrued->epctv_bonuses,
                'third_party_payments' => $accrued->third_party_payments,
                'advances' => $accrued->advances,
                'compensations' => $accrued->compensations,
                'legal_strike' => $accrued->parseStartEndDateQuantityToFormatApi($accrued->legal_strike),

                'HEDs' => $accrued->parseExtraHoursToFormatApi($accrued->heds), //Hora Extra Diurna
                'HENs' => $accrued->parseExtraHoursToFormatApi($accrued->hens), //Hora Extra Nocturna
                'HRNs' => $accrued->parseExtraHoursToFormatApi($accrued->hrns), //Hora Recargo Nocturno
                'HEDDFs' => $accrued->parseExtraHoursToFormatApi($accrued->heddfs), //Hora Extra Diurna Dominical y Festivos
                'HRDDFs' => $accrued->parseExtraHoursToFormatApi($accrued->hrddfs), //Hora Recargo Diurno Dominical y Festivos
                'HENDFs' => $accrued->parseExtraHoursToFormatApi($accrued->hendfs), //Hora Extra Nocturna Dominical y Festivos
                'HRNDFs' => $accrued->parseExtraHoursToFormatApi($accrued->hrndfs), //Hora Recargo Nocturno Dominical y Festivos
            ],
            'deductions' => [
                'eps_type_law_deductions_id' => $deduction->eps_type_law_deductions_id,
                'eps_deduction' => $deduction->eps_deduction,
                'pension_type_law_deductions_id' => $deduction->pension_type_law_deductions_id,
                'pension_deduction' => $deduction->pension_deduction,
                'deductions_total' => $deduction->deductions_total,

                'fondossp_type_law_deductions_id' => $deduction->fondossp_type_law_deductions_id,
                'fondosp_deduction_SP' => $deduction->fondosp_deduction_SP,
                'fondossp_sub_type_law_deductions_id' => $deduction->fondossp_sub_type_law_deductions_id,
                'fondosp_deduction_sub' => $deduction->fondosp_deduction_sub,

                'afc' => $deduction->afc,
                'refund' => $deduction->refund,
                'debt' => $deduction->debt,
                'education' => $deduction->education,
                'voluntary_pension' => $deduction->voluntary_pension,
                'withholding_at_source' => $deduction->withholding_at_source,
                'cooperative' => $deduction->cooperative,
                'tax_liens' => $deduction->tax_liens,
                'supplementary_plan' => $deduction->supplementary_plan,

                'labor_union' => $deduction->labor_union,
                'sanctions' => $deduction->sanctions,
                'orders' => $deduction->orders,
                'third_party_payments' => $deduction->third_party_payments,
                'advances' => $deduction->advances,
                'other_deductions' => $deduction->other_deductions,
            ]
        ];

    }

    
    /**
     * Obtener correlativo desde el api
     *
     * @param  mixed $type_service
     * @param  mixed $ignore_state_document_id
     * @param  mixed $prefix
     */
    public function getConsecutive($type_service, $ignore_state_document_id = false, $prefix = null)
    {
        $connection_api = new HttpConnectionApi($this->company->api_token);
        $url = ($prefix) ? "ubl2.1/payroll/current_number/{$type_service}/{$ignore_state_document_id}/{$prefix}" : "ubl2.1/payroll/current_number/{$type_service}/{$ignore_state_document_id}";

        $send_request_to_api = $connection_api->get($url);

        if(isset($send_request_to_api['success']))
        {
            return $send_request_to_api['number'];
        }

        return null;
    }

        
    /**
     * Envio de correo
     *
     * @param  mixed $request
     * @return array
     */
    public function sendEmail($request)
    {
        $connection_api = new HttpConnectionApi($this->company->api_token);
        $send_request_to_api = $connection_api->sendRequestToApi("ubl2.1/send-email-document-payroll", $request->all(), 'POST');

        return $this->getGeneralResponseFromApi($send_request_to_api, $connection_api);
    }

    
    /**
     *
     * @param  bool $success
     * @param  string $message
     * @return array
     */
    public function responseMessage($success, $message)
    {
        return [
            'success' => $success,
            'message' => $message,
        ];
    }
    
    /**
     * Parsea respuesta api, y retorna arreglo con datos para la vista
     *
     * @param  $response
     * @param  HttpConnectionApi $connection_api
     * @return array
     */
    public function getGeneralResponseFromApi($response, HttpConnectionApi $connection_api)
    {
        
        //error validacion form request api
        if(isset($response['errors']))
        {
            return [
                'success' => false,
                'message' => $connection_api->parseErrorsToString($response['errors'])
            ];
        }

        //si la api retorna true/false, retorna arreglo con estado
        return $response;

    }


    public function throwException($message)
    {
        throw new Exception($message);
    }


}
