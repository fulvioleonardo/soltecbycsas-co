<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use Modules\BusinessTurn\Models\DocumentHotel;
use Modules\BusinessTurn\Models\DocumentTransport;

use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Modules\Factcolombia1\Models\Tenant\{
    TypeDocument,
    StateDocument,
    DetailDocument,
    Currency,
    Country,
    Department,
    City,
    LogDocument,
    PaymentForm,
    PaymentMethod,
};
use DateTime;
use Modules\Factcolombia1\Models\TenantService\{
    TypeEnvironment
};
use Illuminate\Database\Eloquent\Builder;


class Document extends ModelTenant
{

    use HasJsonRelationships;


    // protected $with = ['user', 'soap_type', 'state_type', 'document_type', 'currency_type', 'group', 'items', 'invoice', 'note', 'payments'];

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        // 'state_type_id',
        // 'ubl_version',
        // 'group_id',
        // 'document_type_id',
        // 'series',
        'number',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        // 'currency_type_id',
        // 'purchase_order',
        'quotation_id',
        // 'exchange_rate_sale',
        // 'total_prepayment',
        'total_discount',
        // 'total_charge',
        // 'total_exportation',
        // 'total_free',
        // 'total_taxed',
        // 'total_unaffected',
        // 'total_exonerated',
        // 'total_igv',
        // 'total_base_isc',
        // 'total_isc',
        // 'total_base_other_taxes',
        // 'total_other_taxes',
        // 'total_taxes',
        // 'total_value',
        'total',
        // 'charges',
        // 'discounts',
        // 'prepayments',
        // 'guides',
        // 'related',
        // 'perception',
        // 'detraction',
        // 'legends',
        // 'additional_information',
        // 'filename',
        // 'hash',
        // 'qr',
        // 'has_xml',
        // 'has_pdf',
        // 'has_cdr',
        // 'has_prepayment',
        // 'affectation_type_prepayment',
        'data_json',
        'send_server',
        'shipping_status',
        'sunat_shipping_status',
        'query_status',
        'total_plastic_bag_taxes',
        'sale_note_id',
        'success_shipping_status',
        'success_sunat_shipping_status',
        'success_query_status',
        // 'plate_number',
        'total_canceled',
        'order_note_id',
        // 'soap_shipping_response',

        //co
        'state_document_id',
        'type_document_id',
        'prefix',
        'acknowledgment_received',
        'cufe',
        'xml',
        'type_invoice_id',
        'currency_id',
        'date_expiration',
        'observation',
        'reference_id',
        'note_concept_id',
        'sale',
        'taxes',
        'total_tax',
        'subtotal',
        'version_ubl_id',
        'ambient_id',
        'response_api',
        'payment_form_id',
        'payment_method_id',
        'time_days_credit',
        'correlative_api',
        'response_api_status',
        'order_reference',
        'type_environment_id',
        'shipping_two_steps',
        'response_message_query_zipkey',
        'health_fields'
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'taxes' => 'object',
    ];

    //co

    public function nextConsecutive($type_document_id) {
        $typeDocument = TypeDocument::findOrFail($type_document_id);
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

    public function scopeHasPrefix($query, $prefix = null) {
        if ($prefix == null) return $query;

        return $query->where('prefix', $prefix);
    }

    public function type_environment()
    {
        return $this->belongsTo(TypeEnvironment::class);
    }

    public function state_document() {
        return $this->belongsTo(StateDocument::class);
    }

    public function getCurrencyTypeIdAttribute()
    {
        return $this->currency->name;
    }

    public function detail_documents() {
        return $this->hasMany(DetailDocument::class);
    }

    public function currency() {
        return $this->belongsTo(Currency::class);
    }

    public function reference() {
        return $this->belongsTo(Document::class, 'reference_id');
    }

    public function type_document() {
        return $this->belongsTo(TypeDocument::class);
    }

    public function country_client() {
        return $this->belongsTo(Country::class, 'customer->country_id');
    }

    public function departament_client() {
        return $this->belongsTo(Department::class, 'customer->department_id');
    }

    public function city_client() {
        return $this->belongsTo(City::class, 'customer->city_id');
    }

    public function log_documents() {
        return $this->hasMany(LogDocument::class)->latest();
    }

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
            ];
        }

        $model = json_decode($this->response_api);
        if(array_key_exists('urlinvoicepdf', (array)$model))
            return (object)[
                'message' => $model->message,
                'urlinvoicepdf' => $model->urlinvoicepdf,
                'urlinvoicexml' => $model->urlinvoicexml,
            ];
        else
            return (object)[
                'message' => '',
                'urlinvoicepdf' => '',
                'urlinvoicexml' => '',
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

    /**
     * Retorna el codigo del tipo de documento para enviar a la api
     */
    public function getTypeDocumentService()
    {
        return $this->type_document->code;
    }

    public function getSeriesAttribute()
    {
        return $this->prefix;
    }

    public function getDocumentTypeIdAttribute()
    {
        //equivalent document type invoice peru - colombia

        /*$document_types = [
            1 => '01', //FV
            2 => '08', //ND
            3 => '07', //NC
        ];*/

        return $this->type_document_id; //$document_types[$this->type_document_id];

    }
    //co

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
    }


    public function getDataJsonAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDataJsonAttribute($value)
    {
        $this->attributes['data_json'] = (is_null($value))?null:json_encode($value);
    }

    public function getOrderReferenceAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setOrderReferenceAttribute($value)
    {
        $this->attributes['order_reference'] = (is_null($value))?null:json_encode($value);
    }

    public function setHealthFieldsAttribute($value)
    {
        $this->attributes['health_fields'] = (is_null($value))?null:json_encode($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    // public function state_type()
    // {
    //     return $this->belongsTo(StateType::class);
    // }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }

    // public function group()
    // {
    //     return $this->belongsTo(Group::class);
    // }

    public function document_type()
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }

    // public function currency_type()
    // {
    //     return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    // }

    public function getCompanyAttribute()
    {
        return Company::first();
    }

    // public function invoice()
    // {
    //     return $this->hasOne(Invoice::class);
    // }

    // public function note()
    // {
    //     return $this->hasOne(Note::class);
    // }

    public function items()
    {
        return $this->hasMany(DocumentItem::class);
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function payments()
    {
        return $this->hasMany(DocumentPayment::class);
    }


    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }

    public function hotel()
    {
        return $this->hasOne(DocumentHotel::class);
    }

    public function transport()
    {
        return $this->hasOne(DocumentTransport::class);
    }

    // public function getNumberFullAttribute()
    // {
    //     return $this->series.'-'.$this->number;
    // }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    // public function getResponseApiAttribute($value)
    // {
    //     return (is_null($value))?null:(object) json_decode($value);
    // }

    public function getDownloadExternalXmlAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'xml', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalPdfAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'pdf', 'external_id' => $this->external_id]);
    }

    public function getDownloadExternalCdrAttribute()
    {
        return route('tenant.download.external_id', ['model' => 'document', 'type' => 'cdr', 'external_id' => $this->external_id]);
    }


    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    // public function getStateTypeIdAttribute()
    // {
    //     return '01';
    // }


    public function scopeWhereNotSent($query)
    {
        // return  $query->whereIn('state_type_id', ['01','03'])->where('date_of_issue','<=',date('Y-m-d'));
    }

    // public function affected_documents()
    // {
    //     return $this->hasMany(Note::class, 'affected_document_id');
    // }


    public function summary_document()
    {
        return $this->hasOne(SummaryDocument::class);
    }

    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_document_id', [1, 2, 3, 4, 5]);
        // return $query->whereIn('state_type_id', ['01','03','05','07','13']);
    }

    public function scopeWhereCurrency($query, $currency_id)
    {
        return $query->where('currency_id', $currency_id);
    }

    /**
     *
     * Obtener el total del documento
     *
     * Usado en:
     * Cash - Cierre de caja chica
     *
     * @return double
     */
    // public function getTotalCash()
    // {
    //     return $this->total;
    // }


    /**
     *
     * Filtrar facturas
     *
     * Usado en:
     * Modules\Report\Http\Controllers\ReportItemController
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeFilterInvoiceDocument($query)
    {
        return $query->whereHas('type_document', function($q){
            $q->where('code', TypeDocument::INVOICE_CODE);
        });
    }


    /**
     *
     * Filtrar por cliente
     *
     * @param  Builder $query
     * @param  int $customer_id
     * @return Builder
     */
    public function scopeFilterByCustomer($query, $customer_id)
    {
        if($customer_id) $query->where('customer_id', $customer_id);

        return $query;
    }


    /**
     *
     * Filtrar por usuario
     *
     * @param  Builder $query
     * @param  int $user_id
     * @return Builder
     */
    public function scopeFilterByUser($query, $user_id)
    {
        if($user_id) $query->where('user_id', $user_id);

        return $query;
    }


    /**
     *
     * Filtrar por rango de tiempo
     *
     * @param  Builder $query
     * @param  string $start_time
     * @param  string $end_time
     * @return Builder
     */
    public function scopeFilterByRangeTimeOfIssue($query, $start_time, $end_time)
    {
        $first_second = '00';
        $last_second = '59';

        if($start_time) $query->where('time_of_issue', '>=', "{$start_time}:{$first_second}");

        if($end_time) $query->where('time_of_issue', '<=', "{$end_time}:{$last_second}");

        return $query;
    }


    /**
     *
     * Filtrar por rango de fecha
     *
     * @param  Builder $query
     * @param  string $start_date
     * @param  string $end_date
     * @return Builder
     */
    public function scopeFilterByRangeDateOfIssue($query, $start_date, $end_date)
    {
        return $query->whereBetween('date_of_issue', [$start_date, $end_date]);
    }


    /**
     *
     * Campos base para calculos
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeSelectColumnsForCalculate($query)
    {
        return $query->with([
                        'items' => function($items){
                            return $items->select([
                                'id',
                                'document_id',
                                'item_id',
                                'quantity',
                                'unit_price',
                                'tax_id',
                                'tax',
                                'total_tax',
                                'subtotal',
                                'total'
                            ]);
                        },
                        'type_document' => function($type_document){
                            return $type_document->select([
                                'id',
                                'name',
                            ]);
                        },
                        'currency' => function($currency){
                            return $currency->select([
                                'id',
                                'code',
                            ]);
                        },
                    ])
                    ->select([
                        'id',
                        'type_document_id',
                        'prefix',
                        'number',
                        'date_of_issue',
                        'customer_id',
                        'customer',
                        'total',
                        'currency_id',
                        'sale',
                        'total_tax',
                        'subtotal'
                    ]);
    }


    /**
     *
     * Filtros para reporte libro ventas
     *
     * @param  Builder $query
     * @param  Request $request
     * @return Builder
     */
    public function scopeFilterReportSalesBook($query, $request)
    {
        $customer_id = $request->customer_id ?? null;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? null;

        return $query->filterInvoiceDocument()
                        ->filterByRangeDateOfIssue($start_date, $end_date)
                        ->filterByCustomer($customer_id)
                        ->selectColumnsForCalculate()
                        ->latest();
    }


    /**
     *
     * Reporte libro ventas
     *
     * @return array
     */
    public function getDataReportSalesBook()
    {
        return [
            'date_of_issue' => $this->date_of_issue->format('d/m/Y'),
            'number_full' => $this->number_full,
            'type_document_name' => $this->type_document->name,
            'currency_code' => $this->currency->code,
            'customer_name' => $this->customer->name,
            'net_total' => $this->generalApplyNumberFormat($this->sale),
            'total' => $this->generalApplyNumberFormat($this->total),
            'total_exempt' => $this->generalApplyNumberFormat($this->getTotalExempt()),
        ];
    }


    /**
     *
     * Reporte libro ventas resumido
     *
     * @return array
     */
    public function getDataReportSummarySalesBook()
    {
        return [
            'net_total' => $this->sale,
            'total' => $this->total,
            'total_exempt' => $this->getTotalExempt(),
        ];
    }


    /**
     *
     * Totales por impuestos
     *
     * @param  int $tax_id
     * @return array
     */
    public function getItemValuesByTax($tax_id)
    {
        $items_by_tax = $this->getItemsByTaxId($tax_id);

        $tax_amount = 0;
        $taxable_amount = 0;

        if($items_by_tax->count() > 0)
        {
            $tax_amount = $items_by_tax->sum('total_tax');
            $taxable_amount = $items_by_tax->sum('total') - $tax_amount;
        }

        return [
            'tax_amount' => $this->generalApplyNumberFormat($tax_amount),
            'taxable_amount' => $this->generalApplyNumberFormat($taxable_amount),
        ];
    }


    /**
     * Filtrar items por impuesto
     *
     * @param  int $tax_id
     * @return array
     */
    public function getItemsByTaxId($tax_id)
    {
        return $this->items->where('tax_id', $tax_id);
    }


    /**
     *
     * Obtener total exento del documento
     *
     * @return float
     */
    public function getTotalExempt()
    {
        return $this->items
                    ->filter(function($row){
                        $tax_code = $row->tax->code ?? null;
                        return $tax_code === self::EXEMPT_TAX_CODE;
                    })
                    ->sum('total');
    }


}
