<?php

namespace Modules\Purchase\Models; 
 
use App\Models\Tenant\ModelTenant;
use Modules\Factcolombia1\Models\Tenant\TypeUnit;
use Modules\Factcolombia1\Models\Tenant\Tax;


class PurchaseOrderItem extends ModelTenant
{
    // protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'purchase_order_id',
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

    public function unit_type()
    {
        return $this->belongsTo(TypeUnit::class, 'unit_type_id');
    }


    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    public function getChargesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = (is_null($value))?null:json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = (is_null($value))?null:json_encode($value);
    }

    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}