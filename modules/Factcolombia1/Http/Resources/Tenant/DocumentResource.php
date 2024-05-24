<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceTenantCompany
};

class DocumentResource extends JsonResource
{
     

    public function toArray($request) {

        // $company = ServiceTenantCompany::firstOrFail();
        $company = ServiceTenantCompany::select('identification_number')->whereFilterWithOutAllRelations()->firstOrFail();
        $base_url_api = config('tenant.service_fact');
        $download_xml = "{$base_url_api}download/{$company->identification_number}/{$this->response_api_invoice->urlinvoicexml}";
        $download_pdf = "{$base_url_api}download/{$company->identification_number}/{$this->response_api_invoice->urlinvoicepdf}";

        $response_api_message = null;

        if($this->response_api){
            $response = json_decode($this->response_api);
            $response_api_message = isset($response->message) ? $response->message:null;
        }

        return [
            'id' => $this->id, 
            'correlative_api' => $this->correlative_api, 
            'number_full' => $this->number_full, 
            'customer_email' => $this->customer->email, 
            'customer_phone' => $this->customer->telephone, 
            'response_api_message' => $response_api_message,
            'download_xml' => $download_xml,
            'download_pdf' => $download_pdf,
            'state_document_id' => $this->state_document_id,
            'response_message_query_zipkey' => $this->response_message_query_zipkey,
            'type_environment_id' => $this->type_environment_id,
        ];
            
    }
}
