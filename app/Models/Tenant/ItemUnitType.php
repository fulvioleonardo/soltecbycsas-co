<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use Modules\Factcolombia1\Models\Tenant\TypeUnit;

class ItemUnitType extends ModelTenant
{

    protected $with = ['unit_type'];

    public $timestamps = false;
    
    protected $fillable = [
        'description',
        'item_id',
        'unit_type_id',
        'quantity_unit',
        'price1',
        'price2',
        'price3',
        'price_default',
    ];
    
    public function unit_type() {
        return $this->belongsTo(TypeUnit::class, 'unit_type_id');
    }
    
    public function item() {
        return $this->belongsTo(Item::class);
    }
     
    /**
     * Retornar datos para lista de precios (POS)
     *
     * @return array
     */
    public function getSearchRowResource() 
    {
        return [
            'description' => $this->description,
            'item_id' => $this->item_id,
            'unit_type_id' => $this->unit_type_id,
            'unit_type_name' => trim($this->unit_type->name),
            'quantity_unit' => $this->quantity_unit,
            'price1' => $this->price1,
            'price2' => $this->price2,
            'price3' => $this->price3,
            'price_default' => $this->price_default,
            'unit_type' => $this->unit_type,
        ];
    }

}