<?php

namespace Modules\Factcolombia1\Models\Tenant;

use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class Document extends Model
{
    use SoftDeletes, HasJsonRelationships, UsesTenantConnection;

    protected $table = 'co_documents';


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'client' => 'object',
        'taxes' => 'object',

    ];

    protected $with = ['payment_form', 'payment_method'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['state_document_id', 'type_document_id', 'prefix', 'number', 'xml', 'cufe', 'acknowledgment_received', 'type_invoice_id', 'client_id', 'client', 'currency_id', 'date_issue', 'date_expiration', 'observation', 'reference_id', 'note_concept_id', 'sale', 'total_discount', 'taxes', 'total_tax', 'subtotal', 'total', 'version_ubl_id', 'ambient_id', 'response_api', 'payment_form_id', 'payment_method_id', 'time_days_credit', 'response_api_status', 'correlative_api'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Next consecutive
     * @param  int $type_document_id
     * @return int
     */
    public function nextConsecutive($type_document_id) {
        $typeDocument = TypeDocument::findOrFail($type_document_id);

//        $file = fopen("C:\\DEBUG.TXT", "w");
//        fwrite($file, json_encode($request->GuardarEn).PHP_EOL);
//        fclose($file);

        $typeDocument->number = $typeDocument->from;

        $number = Document::query()
            ->select('number')
            ->where('type_document_id', $type_document_id)
            ->hasPrefix($typeDocument->prefix)
            ->whereBetween('number', [$typeDocument->from, $typeDocument->to])
            ->max('number');

        if (!is_null($number))
        {
            $typeDocument->number = ($number + 1);
        }
        else{
            $typeDocument->number = $typeDocument->from;
        }

        return $typeDocument;
    }

    /**
     * Scope a query to only include prefix.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $prefix
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasPrefix($query, $prefix = null) {
        if ($prefix == null) return $query;

        return $query->where('prefix', $prefix);
    }

    /**
    * Get the state document belongs to
    */
    public function state_document() {
        return $this->belongsTo(StateDocument::class);
    }

    /**
    * Get the detail documents has many
    */
    public function detail_documents() {
        return $this->hasMany(DetailDocument::class);
    }

    /**
    * Get the currency belongs to
    */
    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    /**
    * Get the reference belongs to
    */
    public function reference() {
        return $this->belongsTo(Document::class, 'reference_id');
    }

    /**
    * Get the type document belongs to
    */
    public function type_document() {
        return $this->belongsTo(TypeDocument::class);
    }

    /**
    * Get the country client belongs to
    */
    public function country_client() {
        return $this->belongsTo(Country::class, 'client->country_id');
    }

    /**
    * Get the departament client belongs to
    */
    public function departament_client() {
        return $this->belongsTo(Department::class, 'client->department_id');
    }

    /**
    * Get the city client belongs to
    */
    public function city_client() {
        return $this->belongsTo(City::class, 'client->city_id');
    }

    /**
    * Get the log documents has many
    */
    public function log_documents() {
        return $this->hasMany(LogDocument::class)->latest();
    }

    /**
     * Get the taxes collect
     *
     * @return string
     */
    public function getTaxesCollectAttribute() {
        return collect($this->taxes);
    }

    public function payment_form()
    {
         return $this->belongsTo(PaymentForm::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getPlazoAttribute()
    {
        $ini = $this->created_at;
        $fin  = new DateTime($this->date_expiration);
        $dif =  $ini->diff($fin);
        return $dif->days;
    }

    public function getResponseApiInvoiceAttribute()
    {
        if(!$this->response_api)
        {
            return (object)[
                'message' => '',
                'urlinvoicepdf' => '',
                'urlinvoicexml' => '',
                'urlinvoiceattached' => '',
            ];
        }

        $model = json_decode($this->response_api);
        if(array_key_exists('urlinvoiceattached', $model))
            return (object)[
                'message' => $model->message,
                'urlinvoicepdf' => $model->urlinvoicepdf,
                'urlinvoicexml' => $model->urlinvoicexml,
                'urlinvoiceattached' => $model->urlinvoiceattached,
            ];
        else
            if(array_key_exists('urlinvoicepdf', $model))
                return (object)[
                    'message' => $model->message,
                    'urlinvoicepdf' => $model->urlinvoicepdf,
                    'urlinvoicexml' => $model->urlinvoicexml,
                    'urlinvoiceattached' => ''
                ];
            else
                return (object)[
                    'message' => '',
                    'urlinvoicepdf' => '',
                    'urlinvoicexml' => '',
                    'urlinvoiceattached' => '',
                ];
}

    public function getResponseApiInvoiceStatusAttribute()
    {
        $model = json_decode($this->response_api_status);
        return $model;
    }

    public function getResponseApiInvoiceStatusDateValidAttribute()
    {
        $date_valid = '';
        if($this->response_api_status)
        {
            $model = json_decode($this->response_api_status);
            $status =  (bool)$model->ResponseDian->Envelope->Body->GetStatusZipResponse->GetStatusZipResult->DianResponse->IsValid;
            if($status)
            {
                $date_valid = $this->created_at;
            }
        }


        return $date_valid;
    }

    public function getResponseApiCufeAttribute()
    {
        $model = json_decode($this->response_api);
        return $model->cufe ?? '';
    }

    protected $appends = ['plazo', 'response_api_invoice', 'response_api_invoice_status', 'response_api_invoice_status_date_valid', 'response_api_cufe'];


    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->number;
    }

}
