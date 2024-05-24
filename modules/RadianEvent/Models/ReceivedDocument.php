<?php

namespace Modules\RadianEvent\Models; 

use App\Models\Tenant\{
    ModelTenant,
};
use Modules\Factcolombia1\Models\TenantService\{
    TypeDocument
};
use Illuminate\Database\Eloquent\Builder;


class ReceivedDocument extends ModelTenant
{

    protected $table = 'co_received_documents';

    protected $fillable = [
        
        'identification_number',
        'dv',
        'name_seller',
        'state_document_id',
        'type_document_id',
        'customer',
        'prefix',
        'number',
        'xml',
        'cufe',
        'date_issue',
        'sale',
        'subtotal',
        'total',
        'total_discount',
        'total_tax',
        'ambient_id',
        'pdf',
        'acu_recibo',
        'rec_bienes',
        'aceptacion',
        'rechazo',
        'response_api',
        'co_email_reading_detail_id',
         
    ];


    public function type_document()
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }

    public function email_reading_detail()
    {
        return $this->belongsTo(EmailReadingDetail::class, 'co_email_reading_detail_id');
    }

    public function getResponseApiAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setResponseApiAttribute($value)
    {
        $this->attributes['response_api'] = (is_null($value))?null:json_encode($value);
    }


    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'identification_number' => $this->identification_number,
            'dv' => $this->dv,
            'name_seller' => $this->name_seller,
            'state_document_id' => $this->state_document_id,
            'type_document_id' => $this->type_document_id,
            'type_document_name' => $this->type_document->name,
            'customer' => $this->customer,
            'prefix' => $this->prefix,
            'number' => $this->number,
            'xml' => $this->xml,
            'cufe' => $this->cufe,
            'date_issue' => $this->date_issue,
            'sale' => $this->sale,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'total_discount' => $this->total_discount,
            'total_tax' => $this->total_tax,
            'ambient_id' => $this->ambient_id,
            'pdf' => $this->pdf,
            'acu_recibo' => $this->acu_recibo,
            'rec_bienes' => $this->rec_bienes,
            'aceptacion' => $this->aceptacion,
            'rechazo' => $this->rechazo,
        ];
    }

}
