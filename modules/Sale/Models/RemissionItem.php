<?php

namespace Modules\Sale\Models;

use Illuminate\Support\Facades\DB;
use Modules\Factcolombia1\Models\Tenant\TypeUnit;
use App\Models\Tenant\ModelTenant;

class RemissionItem extends ModelTenant
{

    protected $table = 'co_remission_items';
    
    public $timestamps = false;

    protected $fillable = [

        'remission_id',
        'item_id',
        'item',
        'unit_type_id',
        'tax_id',
        'tax',
        'total_tax',
        'subtotal',
        'discount',
        'quantity', 
        'unit_price',
        'total',

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

    public function remission()
    {
        return $this->belongsTo(Remission::class, 'remission_id');
    }
    
    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

}