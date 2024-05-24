<?php

namespace Modules\Factcolombia1\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use Modules\Factcolombia1\Http\Controllers\Controller;
use Modules\Factcolombia1\Http\Controllers\Tenant\DocumentController as WebDocumentController;
use Modules\Factcolombia1\Http\Resources\Tenant\DocumentCollection;
use Modules\Factcolombia1\Http\Requests\Tenant\DocumentRequest;
use Modules\Factcolombia1\Http\Resources\Tenant\PersonCollection;
use Modules\Factcolombia1\Http\Resources\Tenant\ItemApiCollection;
// use Modules\Factcolombia1\Models\Tenant\Document;
use App\Models\Tenant\Document;
use App\Models\Tenant\Person;
use App\Models\Tenant\Item;

use DB;
use Modules\Document\Traits\SearchTrait;
use Modules\Factcolombia1\Helpers\DocumentHelper;
use Modules\Factcolombia1\Traits\Tenant\DocumentTrait;
use Modules\Factcolombia1\Models\Tenant\Company;
use Modules\Factcolombia1\Models\Tenant\TypeDocument;
use Modules\Factcolombia1\Models\TenantService\Company as ServiceTenantCompany;
use Facades\Modules\Factcolombia1\Models\Tenant\Document as FacadeDocument;

class DocumentController extends Controller
{
    use DocumentTrait, SearchTrait;

    const REGISTERED = 1;
    const ACCEPTED = 5;
    const REJECTED = 6;

    public function tables()
    {
        return (new WebDocumentController)->tables();
    }

    public function store(DocumentRequest $request)
    {
        // dd($request->all());
        // $invoice = $request->all();
        // return (new WebDocumentController)->store($request, json_encode($request->service_invoice));

        // copia controller
        DB::connection('tenant')->beginTransaction();
        try {
            // validacion de tenant
            $this->company = Company::query()->with('country', 'version_ubl', 'type_identity_document')->firstOrFail();
            if (($this->company->limit_documents != 0) && (Document::count() >= $this->company->limit_documents)) {
                return [
                    'success' => false,
                    'message' => '"Has excedido el límite de documentos de tu cuenta."'
                ];
            }
            $response =  null;
            $response_status =  null;
            $ignore_state_document_id = true;
            $base_url = config('tenant.service_fact');
            $id_test = $this->company->test_id;
            $serviceCompany = ServiceTenantCompany::first();
            $token = $serviceCompany->api_token;

            $service_invoice = $this->generateServiceInvoice($request);

            if($this->company->type_environment_id == 2 && $this->company->test_id != 'no_test_set_id') {
                $ch = curl_init("{$base_url}ubl2.1/invoice/{$id_test}");
            } else {
                $ch = curl_init("{$base_url}ubl2.1/invoice");
            }

            $data_document = json_encode($service_invoice);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS,($data_document));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer {$token}"
            ));
            $response = curl_exec($ch);
            $response_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $response_model = json_decode($response);
            $zip_key = null;
            $invoice_status_api = null;

            if(isset($response_model->errors)) {
                return [
                    'success' => false,
                    'message' => $response_model->message,
                    'errors' => $response_model->errors
                ];
            }

            if($response_status_code != 200) {
                return [
                    'success' => false,
                    'message' => $response_model->message,
                    'line' => $response_model->line,
                    'trace' => $response_model->trace[0]
                ];
            }

            if($serviceCompany->type_environment_id == 2 && $serviceCompany->test_id != 'no_test_set_id'){
                if(array_key_exists('urlinvoicepdf', $response_model) && array_key_exists('urlinvoicexml', $response_model))
                {
                    if(!is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey))
                    {
                        if(is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ErrorMessageList->XmlParamsResponseTrackId->Success))
                        {
                            if($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ErrorMessageList->XmlParamsResponseTrackId->Success == 'false')
                            {
                                return [
                                    'success' => false,
                                    'message' => $response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ErrorMessageList->XmlParamsResponseTrackId->ProcessedMessage
                                ];
                            }
                        }
                    }
                    else
                        if(is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey))
                        {
                            $zip_key = $response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey;
                        }
                }

                $response_status = null;

            }
            else{
                if($response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->IsValid == "true")
                    $this->setStateDocument(1, $correlative_api);
                else
                {
                    if(is_array($response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string))
                        $mensajeerror = implode(",", $response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string);
                    else
                        $mensajeerror = $response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->ErrorMessage->string;
                }
            }

            $prefix = (object)['prefix' => $service_invoice['prefix']];
            $this->document = DocumentHelper::createDocument($request, $prefix, $service_invoice['number'], $this->company, $response, $response_status, $serviceCompany->type_environment_id);
            // $payments = (new DocumentHelper())->savePayments($this->document, $request->payments); // no tiene payments el json
        }
        catch (\Exception $e) {

            \Log::error($e);
            // dd($e);
            DB::connection('tenant')->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ];
        }

        DB::connection('tenant')->commit();
        $this->company = Company::query()->with('country', 'version_ubl', 'type_identity_document')->firstOrFail();
        if (($this->company->limit_documents != 0) && (Document::count() >= $this->company->limit_documents - 10))
            $over = ", ADVERTENCIA, ha consumido ".Document::count()." documentos de su cantidad contratada de: ".$this->company->limit_documents;
        else
            $over = "";

        $document_helper = new DocumentHelper();
        if($response_model->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->IsValid == 'true'){
            $document_helper->updateStateDocument(self::ACCEPTED, $this->document);
            return [
                'success' => true,
                'validation_errors' => false,
                'message' => "Se registro con éxito el documento #{$this->document->prefix}{$this->document->number}. {$over}",
                'data' => [
                    'id' => $this->document->id,
                    'document' => $this->document
                ]
            ];
        }
        else{
            $document_helper->updateStateDocument(self::REJECTED, $this->document);
            return [
                'success' => true,
                'validation_errors' => true,
                'message' => "Error al Validar Factura Nro: #{$this->document->prefix}{$this->document->number}., Sin embargo se guardo la factura para posterior envio, ... Errores: ".$mensajeerror." {$over}",
                'data' => [
                    'id' => $this->document->id,
                    'document' => $this->document
                ]
            ];
        }
    }

    public function searchItems(Request $request)
    {
        $records = Item::query()
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%')
                    ->orWhere('description', 'like', '%' . $request->name . '%')
                    ->orwhere('internal_id', 'like', '%' .$request->name . '%');
            });

        return new ItemApiCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function searchDocuments(Request $request)
    {
        $records = Document::query()
            ->when($request->has('serie'), function ($query) use ($request) {
                $query->where('prefix', 'like', '%' . $request->serie . '%');
            })
            ->when($request->has('number'), function ($query) use ($request) {
                $query->where('number', 'like', '%' . $request->number . '%');
            });

        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function searchCustomers(Request $request)
    {
        $records = Person::query()
            ->when($request->has('name'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->name . '%');
            })
            ->when($request->has('number'), function ($query) use ($request) {
                $query->where('number', 'like', '%' . $request->number . '%');
            });

        return new PersonCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function getNextConsecutive($form)
    {
        $base_url = config('tenant.service_fact');
        $token = ServiceTenantCompany::first()->api_token;
        $data_document = json_encode($form);

        $ch = curl_init("{$base_url}ubl2.1/invoice/current_number/{$form['type_document_id']}/{$form['prefix']}/{$form['ignore_state']}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            "Authorization: Bearer {$token}"
        ));
        $response = curl_exec($ch);
        $response_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response_model = json_decode($response);
        /*
         * number is current - no next
            {
                "number": 991500982,
                "success": true,
                "prefix": "SETP"
            }
        */
        if($response_status_code == 200) {
            return $response_model->number+1;
        }
    }

    private function generateServiceInvoice($request)
    {
        $service_invoice = $request->service_invoice;
        $company = ServiceTenantCompany::first();
        $to_get_number = [
            'type_document_id' => $service_invoice['type_document_id'],
            'prefix' => $service_invoice['prefix'],
            'ignore_state' => ($company->type_environment_id === 2)
        ];

        $service_invoice['number'] = $this->getNextConsecutive($to_get_number);
        $service_invoice['foot_note'] = "Modo de operación: Software Propio - by ".env('APP_NAME', 'FACTURALATAM');
        $service_invoice['web_site'] = env('APP_NAME', 'FACTURALATAM');
        $service_invoice['notes'] = $request->observation;
        $service_invoice['date'] = date('Y-m-d', strtotime($request->date_issue));
        $service_invoice['time'] = date('H:i:s');
        $service_invoice['payment_form']['payment_form_id'] = $request->payment_form_id;
        $service_invoice['payment_form']['payment_method_id'] = $request->payment_method_id;
        $service_invoice['payment_form']['duration_measure'] = $request->time_days_credit;
        $service_invoice['payment_form']['payment_due_date'] = $request->payment_form_id == '1' ? date('Y-m-d') : date('Y-m-d', strtotime($request->date_expiration));
        $service_invoice['ivaresponsable'] = $this->company->type_regime->name;
        $service_invoice['nombretipodocid'] = $this->company->type_identity_document->name;
        $service_invoice['tarifaica'] = $this->company->ica_rate;
        $service_invoice['actividadeconomica'] = $this->company->economic_activity_code;
        $service_invoice['customer']['dv'] = $this->validarDigVerifDIAN($service_invoice['customer']['identification_number']);

        // sucursal
        $sucursal = \App\Models\Tenant\Establishment::where('id', auth()->user()->establishment_id)->first();
        $service_invoice['establishment_name'] = $sucursal->description;
        if($sucursal->address != '-') {
            $service_invoice['establishment_address'] = $sucursal->address;
        }
        if($sucursal->telephone != '-') {
            $service_invoice['establishment_phone'] = $sucursal->telephone;
        }
        if(!is_null($sucursal->establishment_logo)) {
            if(file_exists(public_path('storage/uploads/logos/'.$sucursal->id."_".$sucursal->establishment_logo))){
                $establishment_logo = base64_encode(file_get_contents(public_path('storage/uploads/logos/'.$sucursal->id."_".$sucursal->establishment_logo)));
                $service_invoice['establishment_logo'] = $establishment_logo;
            }
        }
        if(!is_null($sucursal->email)) {
            $service_invoice['establishment_email'] = $sucursal->email;
        }
        // end sucursal

        if(!is_null($this->company->jpg_firma_facturas)){
            if(file_exists(public_path('storage/uploads/logos/'.$this->company->jpg_firma_facturas))){
                $firma_facturacion = base64_encode(file_get_contents(public_path('storage/uploads/logos/'.$this->company->jpg_firma_facturas)));
                $service_invoice['firma_facturacion'] = $firma_facturacion;
            }
        }

        // $datoscompany = Company::with('type_regime', 'type_identity_document')->firstOrFail(); // $this->company ya lo posee
        if(file_exists(storage_path('template.api'))){
            $service_invoice['invoice_template'] = "one";
            $service_invoice['template_token'] = password_hash($this->company->identification_number, PASSWORD_DEFAULT);
        }

        if(file_exists(storage_path('sendmail.api'))) {
            $service_invoice['sendmail'] = true;
        }

        return $service_invoice;
    }
}