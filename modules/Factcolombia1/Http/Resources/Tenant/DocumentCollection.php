<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\Factcolombia1\Models\TenantService\{
    Company as ServiceTenantCompany
};

class DocumentCollection extends ResourceCollection
{


    public function toArray($request) {

        $company = ServiceTenantCompany::select('identification_number')->whereFilterWithOutAllRelations()->firstOrFail();

        return $this->collection->transform(function($row, $key) use ($company){

            $base_url_api = config('tenant.service_fact');
            if($row->response_api_invoice->urlinvoicexml)
                $download_xml = "{$base_url_api}download/{$company->identification_number}/{$row->response_api_invoice->urlinvoicexml}";
            else
                $download_xml = "{$base_url_api}download/{$company->identification_number}/FES-{$row->prefix}{$row->number}.xml";
            if($row->response_api_invoice->urlinvoicepdf)
                $download_pdf = "{$base_url_api}download/{$company->identification_number}/{$row->response_api_invoice->urlinvoicepdf}";
            else
                $download_pdf = "{$base_url_api}download/{$company->identification_number}/FES-{$row->prefix}{$row->number}.pdf";

            //mostrar el boton consultar si el estado es registrado y el entorno es habilitacion
            // shipping_two_steps aplica para documentos generados en habilitacion

            $btn_query = false;

            if($row->state_document_id === 1 && $row->type_environment_id == 2 && $row->shipping_two_steps){
                $btn_query = true;
            }

            return [
                'id' => $row->id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'download_xml' => $download_xml,
                'download_pdf' => $download_pdf,
                'response_api_invoice' => $row->response_api_invoice,
                'acknowledgment_received' => ($row->acknowledgment_received != null) ? ($row->acknowledgment_received == 1 ? 'Aceptado' : 'Rechazado'):'',
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'customer_identity_document_type' => $row->customer->identity_document_type->name,
                'number_full' => "{$row->prefix}-{$row->number}",
                'type_document_name' => $row->type_document->name,
                'state_document_name' => $row->state_document->name,
                'currency_name' => $row->currency->name,
                'sale' => $row->sale,
                'total_discount' => $row->total_discount,
                'total_tax' => $row->total_tax,
                'subtotal' => $row->subtotal,
                'total' => $row->total,
                'type_environment_id' => $row->type_environment_id,
                'state_document_id' => $row->state_document_id,
                'btn_query' => $btn_query,

            ];

        });
    }
}
