<?php

namespace Modules\Factcolombia1\Http\Controllers\Tenant;

use Facades\App\Models\Tenant\Document as FacadeDocument;
use Modules\Factcolombia1\Traits\Tenant\DocumentTrait;
use Modules\Factcolombia1\Http\Controllers\Controller;
use Modules\Factcolombia1\Http\Requests\Tenant\{
    QuotationUpdateRequest,
    QuotationRequest
};
use Illuminate\Http\Request;
use Modules\Factcolombia1\Models\Tenant\{
    TypeIdentityDocument,
    DetailQuotation,
    DetailDocument,
    TypeDocument,
    TypeInvoice,
    Quotation,
    Currency,
    Document,
    Company,
    Client,
    Item,
    Tax
};
use Carbon\Carbon;
use Mpdf\Mpdf;
use DB;
use Modules\Factcolombia1\Models\TenantService\{
    Company as TenantServiceCompany
};

use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceTenantCompany
};
use PDF;

class QuotationController extends Controller
{
    use DocumentTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('quotation.tenant.index');
    }
    
    public function create() {
        return view('quotation.tenant.create');
    }
    
    /**
     * All
     * @return \Illuminate\Http\Response
     */
    public function all() {
        return [
            'typeDocuments' => TypeDocument::all(),
            'typeInvoices' => TypeInvoice::all(),
            'documents' => Quotation::query()
                ->with('state_quote', 'currency', 'detail_quotations')
                ->get(),
            'currencies' => Currency::all(),
            'clients' => Client::all(),
            'items'  => Item::query()
                ->with('typeUnit', 'tax')
                ->get(),
            'taxes' => Tax::all()
        ];
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Tenant\QuotationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuotationRequest $request) {
        DB::connection('tenant')->beginTransaction();
        
        try {
            $company = Company::firstOrFail();
            
            $quotation = Quotation::create([
                'client_id' => $request->client_id,
                'client' => Client::findOrFail($request->client_id),
                'currency_id' => $request->currency_id,
                'observation' => $request->observation,
                'sale' => $request->sale,
                'total_discount' => $request->total_discount,
                'taxes' => $request->taxes,
                'total_tax' => $request->total_tax,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'version_ubl_id' => $company->version_ubl_id,
                'ambient_id' => $company->ambient_id
            ]);
            
            foreach ($request->items as $item) {
                DetailQuotation::create([
                    'quotation_id' => $quotation->id,
                    'item_id' => $item['id'],
                    'item' => $item,
                    'type_unit_id' => $item['type_unit_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'tax_id' => $item['tax_id'],
                    'tax' => Tax::find($item['tax_id']),
                    'total_tax' => $item['total_tax'],
                    'subtotal' => $item['subtotal'],
                    'discount' => $item['discount'],
                    'total' => $item['total']
                ]);
            }
        }
        catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        
        DB::connection('tenant')->commit();
        
        return [
            'success' => true,
            'message' => "Se registro con éxito la cotización #{$quotation->id}."
        ];
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Tenant\Request  $request
     * @param  \App\Models\Tenant\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(QuotationUpdateRequest $request, Quotation $quotation) {
        DB::connection('tenant')->beginTransaction();
        
        try {
            $company = Company::firstOrFail();
            
            $quotation->update([
                'client_id' => $request->client_id,
                'client' => Client::findOrFail($request->client_id),
                'currency_id' => $request->currency_id,
                'sale' => $request->sale,
                'total_discount' => $request->total_discount,
                'taxes' => $request->taxes,
                'total_tax' => $request->total_tax,
                'subtotal' => $request->subtotal,
                'total' => $request->total,
                'version_ubl_id' => $company->version_ubl_id,
                'ambient_id' => $company->ambient_id
            ]);
            
            $quotation->detail_quotations()->delete();
            
            foreach ($request->items as $item) {
                DetailQuotation::create([
                    'quotation_id' => $quotation->id,
                    'item_id' => $item['id'],
                    'item' => $item,
                    'type_unit_id' => $item['type_unit_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'tax_id' => $item['tax_id'],
                    'tax' => Tax::find($item['tax_id']),
                    'total_tax' => $item['total_tax'],
                    'subtotal' => $item['subtotal'],
                    'discount' => $item['discount'],
                    'total' => $item['total']
                ]);
            }
        }
        catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        
        DB::connection('tenant')->commit();
        
        return [
            'success' => true,
            'message' => "Se actualizo con éxito la cotización #{$quotation->id}."
        ];
    }
    
    /**
     * To bill
     * @param  \App\Models\Tenant\Quotation $quotation
     * @return \Illuminate\Http\Response
     */
    public function toBill(Quotation $quotation, Request $request) {
        

        
        try {

            $correlative_api = $this->getCorrelativeInvoice(1);
            // return $correlative_api;
 
            if(!is_numeric($correlative_api)){
                 return [
                     'success' => false,
                     'message' => 'Error al obtener correlativo Api.'
                 ];
            }

            $service_invoice = $request->service_invoice;
            $service_invoice['number'] = $correlative_api;

            $response =  null;
            $response_status =  null;

            $company = ServiceTenantCompany::firstOrFail();
            $id_test = $company->test_id;
            $base_url = env("SERVICE_FACT", "");
            $ch = curl_init("{$base_url}ubl2.1/invoice/{$id_test}");
            $data_document = json_encode($service_invoice);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS,($data_document));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer {$company->api_token}"
            ));
            $response = curl_exec($ch);
            curl_close($ch);

            
            $response_model = json_decode($response);
            $zip_key = null;
            $invoice_status_api = null;

            if( property_exists($response_model, 'urlinvoicepdf') &&  property_exists($response_model, 'urlinvoicexml') )
            {
                if(is_string($response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey))
                {
                    $zip_key = $response_model->ResponseDian->Envelope->Body->SendTestSetAsyncResponse->SendTestSetAsyncResult->ZipKey;
                }
              
            }

            $response_status = null;

            if($zip_key)
            {
                //espero 3 segundos para ejecutar sevcio de status document
                sleep(3);

                $ch2 = curl_init("{$base_url}ubl2.1/status/zip/{$zip_key}");
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    "Authorization: Bearer {$company->api_token}"
                ));
                $response_status = curl_exec($ch2);
                curl_close($ch2);
                
            }


            DB::connection('tenant')->beginTransaction();
            $nextConsecutive = FacadeDocument::nextConsecutive(1);
            
            $this->company = Company::query()
                ->with('country', 'version_ubl', 'type_identity_document')
                ->firstOrFail();
            
            if (($this->company->limit_documents != 0) && (Document::count() >= $this->company->limit_documents)) throw new \Exception("Has excedido el límite de documentos de tu cuenta.");
            
            $this->document = Document::create([
                'type_document_id' => 1,
                'prefix' => $nextConsecutive->prefix,
                'number' => $nextConsecutive->number,
                'type_invoice_id' => 1,
                'client_id' => $quotation->client_id,
                'client' => Client::with('typePerson', 'typeRegime', 'typeIdentityDocument', 'country', 'department', 'city')->findOrFail($quotation->client_id),
                'currency_id' => $quotation->currency_id,
                'date_issue' => Carbon::now(),
                'observation' => $quotation->observation,
                'sale' => $quotation->sale,
                'total_discount' => $quotation->total_discount,
                'taxes' => $quotation->taxes,
                'total_tax' => $quotation->total_tax,
                'subtotal' => $quotation->subtotal,
                'total' => $quotation->total,
                'version_ubl_id' => $quotation->version_ubl_id,
                'ambient_id' => $quotation->ambient_id,

                'payment_form_id' => 1,
                'payment_method_id' =>10,
                'time_days_credit' =>0,

                'response_api' => $response,
                'response_api_status' => $response_status,
                'correlative_api' => $correlative_api
            ]);
            
            $this->document->update([
                'xml' => $this->getFileName(),
                'cufe' => $this->getCufe()
            ]);
            
            foreach ($quotation->detail_quotations as $item) {
                DetailDocument::create([
                    'document_id' => $this->document->id,
                    'item_id' => $item->item_id,
                    'item' => $item->item,
                    'type_unit_id' => $item->type_unit_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'tax_id' => $item->tax_id,
                    'tax' => $item->tax,
                    'total_tax' => $item->total_tax,
                    'subtotal' => $item->subtotal,
                    'discount' => $item->discount,
                    'total' => $item->total
                ]);
            }
            
           // $this->saveFileAndSignDocument();
            //$this->send();
            
            $quotation->update([
                'state_quote_id' => 2
            ]);
        }
        catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        
        DB::connection('tenant')->commit();
        
        return [
            'success' => true,
            'message' => "Se registro con éxito el documento #{$this->document->prefix}{$this->document->number}."
        ];
    }
    
    /**
     * Download
     * @param  \App\Models\Tenant\Quotation $quotation
     * @param  Illuminate\Http\Request   $request
     * @return \Illuminate\Http\Response
     */
    public function download(Quotation $quotation, Request $request) {
        $typeIdentityDocuments = TypeIdentityDocument::all();
        $company = Company::firstOrFail();
        $servicecompany = TenantServiceCompany::firstOrFail();
        $document = $quotation;
        $pdf = PDF::loadView("pdf/quotation", compact("typeIdentityDocuments", "company", "servicecompany", "document"))->setWarnings(false);
        return $pdf->download("COT{$quotation->id}.pdf");
    }

    public function getCorrelativeInvoice($type_service)
    {
      
        $company = ServiceTenantCompany::firstOrFail();
        $base_url = env("SERVICE_FACT", "");
        $ch2 = curl_init("{$base_url}ubl2.1/invoice/current_number/{$type_service}");
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
            "Authorization: Bearer {$company->api_token}"
        ));
        $response_data = curl_exec($ch2);
        $err = curl_error($ch2);
        curl_close($ch2);
        $response_encode = json_decode($response_data);
        if($err){
            return null;
        }
        else{
            return $response_encode->number;
        }

    }
}
