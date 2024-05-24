<?php

namespace App\Models\Tenant;

use Modules\Factcolombia1\Models\Tenant\TypeUnit;

class QuotationItem extends ModelTenant
{

    // protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'quotation_id',
        'item_id',
        'item',
        'quantity',
        'unit_price',
        'total',

        'unit_type_id',
        'tax_id',
        'tax',
        'total_tax',
        'subtotal',
        'discount',
    ];

    protected $casts = [
        'tax' => 'object'
    ];

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function unit_type()
    {
        return $this->belongsTo(TypeUnit::class, 'unit_type_id');
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    

}