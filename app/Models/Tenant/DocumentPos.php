<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\CurrencyType;
use Modules\Factcolombia1\Models\Tenant\{
    Currency,
};

class DocumentPos extends ModelTenant
{
    protected $with = ['state_type', 'currency', 'items', 'payments'];

    protected $table = 'documents_pos';

    protected $fillable = [
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'prefix',
        'date_of_issue',
        'time_of_issue',
        'customer_id',
        'customer',
        'exchange_rate_sale',
        'total',
        'filename',
        'total_canceled',
        'quotation_id',
        'order_note_id',
        'apply_concurrency',
        'type_period',
        'quantity_period',
        'automatic_date_of_issue',
        'enabled_concurrency',
        'series',
        'number',
        'paid',
        'license_plate',
        //co
        'currency_id',
        'sale',
        'taxes',
        'total_tax',
        'subtotal',
        'total_discount',
        'electronic',
        'cude',
        'request_api',
        'response_api',
        'ambient_id',
        'note_concept_id',
        'qr',
    ];

    protected $casts = [
        'date_of_issue' => 'date',
        'automatic_date_of_issue' => 'date',
        'taxes' => 'object',
        'request_api' => 'object',
        'response_api' => 'object',
    ];


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

    public function getIdentifierAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }


    public function currency() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    //legacy
    public function currency_type() {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getCurrencyTypeIdAttribute()
    {
        return $this->currency->name;
    }

    public function items()
    {
        return $this->hasMany(DocumentPosItem::class);
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function inventory_kardex()
    {
        return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
    }

    public function payments()
    {
        return $this->hasMany(DocumentPosPayment::class, 'document_pos_id');
    }

   /* public function documents()
    {
        return $this->hasMany(Document::class);
    }*/


    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function getNumberFullAttribute()
    {
        return "{$this->series}-{$this->number}";
    }


    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }


    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01','03','05','07','13']);
    }

    public function scopeWhereNotChanged($query)
    {
        return $query->where('changed', false);
    }


    /*public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }*/

    public function scopeWhereCurrency($query, $currency_id)
    {
        return $query->where('currency_id', $currency_id);
    }

    public function getTypeDocumentAttribute()
    {
        return ['name'=> 'Factura POS'];
    }

    public function getReferenceAttribute()
    {
        return null;
    }

    protected $appends = [
        'type_document',
        'reference'
    ];


    /**
     *
     * Obtener el total del documento
     *
     * Usado en:
     * Cash - Cierre de caja chica/Reporte
     *
     * @return double
     */
    public function getTotalCash()
    {
        return ($this->state_type_id === '11') ? 0 : $this->total;
    }

    public function getDocumentTypeDescription()
    {
        return 'FACT POS';
    }

    /**
     * Obetener arreglo con los datos necesarios para mostrar en vista/reporte
     *
     * Usado en:
     * DocumentPosCollection - Reportes
     *
     * @return array
     */
    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'customer_name' => $this->customer->name,
            'customer_number' => $this->customer->number,
            'currency_type_id' => $this->currency->name,
            'total' => number_format($this->total,2),
            'state_type_id' => $this->state_type_id,
            'state_type_description' => $this->state_type->description,
            'user_name' => optional($this->user)->name,
            'number_full' => $this->number_full,
            'document_type_description' => $this->getDocumentTypeDescription(),
        ];
    }


    public function cash_document()
    {
        return $this->hasOne(CashDocument::class);
    }


    /**
     *
     * Obtener resolucion de la caja asociada al documento
     *
     * @return ConfigurationPos
     */
    public function getCashResolution()
    {
        $resolution = null;

        if($this->cash_document->cash->resolution ?? false)
        {
            // para documentos registrados
            $resolution = $this->cash_document->cash->resolution;
        }
        else
        {
            // para documentos en proceso de creacion
            $resolution = $this->getResolutionFromCurrentCash();
        }

        return $resolution;
    }


    /**
     *
     * Buscar caja actual asociada al pos y retornar resolución
     *
     * @return ConfigurationPos
     */
    public function getResolutionFromCurrentCash()
    {
        $cash = Cash::getOpenCurrentCash()->first();

        return $cash->resolution;
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
                                'document_pos_id',
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
                        'currency' => function($currency){
                            return $currency->select([
                                'id',
                                'code',
                            ]);
                        },
                    ])
                    ->select([
                        'id',
                        'series',
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

        return $query->filterByRangeDateOfIssue($start_date, $end_date)
                        ->filterByCustomer($customer_id)
                        ->selectColumnsForCalculate()
                        ->latest();
    }


    /**
     * Total neto
     *
     * @return float
     */
    public function getNetTotalAttribute()
    {
        return $this->subtotal - $this->total_tax;
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
            'type_document_name' => 'Documento POS',
            'currency_code' => $this->currency->code,
            'customer_name' => $this->customer->name,
            'net_total' => $this->generalApplyNumberFormat($this->net_total),
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
            'net_total' => $this->net_total,
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
