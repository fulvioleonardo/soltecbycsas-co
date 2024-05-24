<?php

namespace Modules\Purchase\Models; 

use App\Models\Tenant\{
    ModelTenant,
    Establishment,
    User,
    Person,
};
use Modules\Factcolombia1\Models\Tenant\{
    TypeDocument,
    StateDocument,
    Currency,
    PaymentForm,
    PaymentMethod,
};
use Modules\Factcolombia1\Models\TenantService\{
    TypeEnvironment
};
use Illuminate\Database\Eloquent\Builder;

class SupportDocument extends ModelTenant
{

    protected $table = 'co_support_documents';

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',

        'supplier_id',
        'supplier',
        
        'ambient_id',
        'version_ubl_id',

        'type_environment_id',
        'state_document_id',
        'date_of_issue',
        'time_of_issue',
        'currency_id',
        'type_document_id',
        'prefix',
        'number',

        'payment_form_id',
        'payment_method_id',
        'time_days_credit',

        'sale',
        'total_tax',
        'subtotal',
        'total_discount',
        'total',

        'acknowledgment_received',
        'cufe',
        'xml',
        'date_expiration',
        'observation',
        'taxes',
        'response_api',
         
    ];

    protected $casts = [
        'date_of_issue' => 'date',
    ];


    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getSupplierAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setSupplierAttribute($value)
    {
        $this->attributes['supplier'] = (is_null($value))?null:json_encode($value);
    }
    
    public function getTaxesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setTaxesAttribute($value)
    {
        $this->attributes['taxes'] = (is_null($value))?null:json_encode($value);
    }

    public function getResponseApiAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setResponseApiAttribute($value)
    {
        $this->attributes['response_api'] = (is_null($value))?null:json_encode($value);
    }


    public function type_environment()
    {
        return $this->belongsTo(TypeEnvironment::class);
    }

    public function state_document() 
    {
        return $this->belongsTo(StateDocument::class);
    }
    
    public function currency() 
    {
        return $this->belongsTo(Currency::class);
    }
    
    public function type_document() 
    {
        return $this->belongsTo(TypeDocument::class);
    }
    
    public function payment_form()
    {
        return $this->belongsTo(PaymentForm::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
    
    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->number;
    }
    
     
    /**
     * Retorna el codigo del tipo de documento para enviar a la api
     *
     * @return void
     */
    public function getTypeDocumentService()
    {
        return $this->type_document->code;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function person() 
    {
        return $this->belongsTo(Person::class, 'supplier_id');
    }
    
    public function support_document_adjust_note() 
    {
        return $this->hasOne(SupportDocumentAdjustNote::class, 'co_support_document_id');
    }
    
    public function items()
    {
        return $this->hasMany(SupportDocumentItem::class, 'co_support_document_id');
    }
    
    public function adjust_notes()
    {
        return $this->hasMany(SupportDocumentAdjustNote::class, 'affected_support_document_id');
    }
    
    /**
     *
     * @return string
     */
    public function getCuds()
    {
        return $this->response_api->cuds;
    }


    /**
     *
     * @return bool
     */
    public function isAdjustNote()
    {
        return $this->type_document->code === '13';
    }

    
    /**
     * 
     * Datos de documento de soporte para crear nota de ajuste
     *
     * @param  int $id
     * @return array
     */
    public static function getDataAdjustNote($id)
    {
        $data = self::with(['items'])->findOrFail($id);

        $data->items->transform(function($row){

            $new_row = $row;
            $new_row['id'] = null;
            $new_row['type_generation_transmition_id'] = null;
            $new_row['start_date'] = null;
            $new_row['price'] = $new_row['unit_price'];
            $new_row['code'] = null;
            $new_row['name'] = null;
            unset($new_row['co_support_document_id']);
            return $new_row;

        });

        return $data;
    }

    public function getRowResource()
    {
        
        $filename_xml = null;
        $filename_pdf = null;
        $response_api_message = null;
        
        if($this->response_api)
        {
            $response = $this->response_api;
            $response_dian_message = $response->ResponseDian->Envelope->Body->SendBillSyncResponse->SendBillSyncResult->StatusMessage ?? '';
            $response_api_message = isset($response->message) ? $response->message : null;
            $filename_xml = $response->urlinvoicexml ?? null;
            $filename_pdf =  $response->urlinvoicepdf ?? null;
        }

        $support_document_relateds = $this->getSupportDocumentRelated();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'external_id' => $this->external_id,
            'establishment_id' => $this->establishment_id,
            'supplier_id' => $this->supplier_id,
            'supplier_full_name' => $this->supplier->name,
            'type_environment_id' => $this->type_environment_id,
            'state_document_id' => $this->state_document_id,
            'state_document_name' => $this->state_document->name,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'time_of_issue' => $this->time_of_issue,
            'currency_id' => $this->currency_id,
            'currency_name' => $this->currency->name,
            'type_document_id' => $this->type_document_id,
            'type_document_name' => $this->type_document->name,
            'prefix' => $this->prefix,
            'number' => $this->number,
            'number_full' => $this->number_full,
            'payment_form_id' => $this->payment_form_id,
            'payment_method_id' => $this->payment_method_id,
            'time_days_credit' => $this->time_days_credit,
            'total' => $this->total,
            'is_adjust_note' => $this->isAdjustNote(),

            'response_dian_message' => $response_dian_message,
            'response_api_message' => $response_api_message,
            'filename_xml' => $filename_xml,
            'filename_pdf' => $filename_pdf,
            'support_document_relateds' => $support_document_relateds,

        ];
    }

    
    /**
     * 
     * Obtener documentos de soporte relacionados
     *
     * @return array
     */
    public function getSupportDocumentRelated()
    {
        $adjust_notes = collect();

        if($this->isAdjustNote())
        {
            $adjust_notes->push([
                'number_full' => $this->support_document_adjust_note->affected_support_document->number_full,
            ]);
        }
        else
        {
            $adjust_notes = $this->adjust_notes->transform(function($row){
                return $row->getSupportDocumentAdjustNote();
            });
        }

        return $adjust_notes;
    }

}
