<?php

namespace App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\Catalogs\UnitType;
use Modules\Account\Models\Account;
use Modules\Item\Models\Category;
use Modules\Item\Models\Brand;
use Modules\Item\Models\ItemLot;
use Modules\Item\Models\ItemLotsGroup;

use Modules\Factcolombia1\Models\Tenant\TypeUnit;
use Modules\Factcolombia1\Models\Tenant\Tax;
use Modules\Factcolombia1\Models\Tenant\Currency;
use Modules\Inventory\Models\Warehouse;


class Item extends ModelTenant
{
    protected $with = ['item_type', 'unit_type', 'warehouses','item_unit_types', 'tags'];

    protected $fillable = [
        'warehouse_id',
        'name',
        'second_name',
        'description',
        'item_type_id',
        'internal_id',
        // 'item_code',
        // 'item_code_gs1',
        'unit_type_id',
        'currency_type_id',
        'sale_unit_price',
        'purchase_unit_price',
        // 'has_isc',
        // 'system_isc_type_id',
        // 'percentage_isc',
        // 'suggested_price',

        // 'sale_affectation_igv_type_id',
        // 'purchase_affectation_igv_type_id',
        'calculate_quantity',
        // 'has_igv',

        'stock',
        'stock_min',
        'percentage_of_profit',

        'attributes',
        'has_perception',
        'percentage_perception',
        'image',
        'image_medium',
        'image_small',

        'account_id',
        'amount_plastic_bag_taxes',
        'date_of_due',
        'is_set',
        'sale_unit_price_set',
        'apply_store',
        'brand_id',
        'category_id',
        'lot_code',
        'lots_enabled',
        'active',
        'series_enabled',
        'tax_id',
        'purchase_tax_id',
        'model',
        // 'warehouse_id'
    ];


    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function item_type()
    {
        return $this->belongsTo(ItemType::class);
    }

    // public function unit_type()
    // {
    //     return $this->belongsTo(UnitType::class, 'unit_type_id');
    // }

    //colombia

    public function unit_type()
    {
        return $this->belongsTo(TypeUnit::class, 'unit_type_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function purchase_tax()
    {
        return $this->belongsTo(Tax::class, 'purchase_tax_id');
    }

    //use legacy

    public function currency_type()
    {
        return $this->belongsTo(Currency::class, 'currency_type_id');
    }

    //colombia



    // public function system_isc_type()
    // {
    //     return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    // }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    public function inventory_kardex()
    {
        return $this->hasMany(InventoryKardex::class);
    }

    public function purchase_item()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    // public function sale_affectation_igv_type()
    // {
    //     return $this->belongsTo(AffectationIgvType::class, 'sale_affectation_igv_type_id');
    // }

    // public function purchase_affectation_igv_type()
    // {
    //     return $this->belongsTo(AffectationIgvType::class, 'purchase_affectation_igv_type_id');
    // }

     public function scopeWhereWarehouse($query)
     {
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
        if ($warehouse) {
            return $query->whereHas('warehouses', function($query) use($warehouse) {
                            $query->where('warehouse_id', $warehouse->id);
                        })->orWhere('unit_type_id', 'ZZ');
        }
        return $query;
     }

     public function scopeWhereNotItemsAiu($query)
     {
        return $query->whereNotIn('internal_id', ['aiu00001', 'aiu00002', 'aiu00003']);
     }

    public function scopeWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $this->scopeWhereWarehouse($query) : null;
    }

    public function scopeWhereNotIsSet($query)
    {
        return $query->where('is_set', false);
    }

    public function scopeWhereIsActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeWhereIsSet($query)
    {
        return $query->where('is_set', true);
    }

    public function getStockByWarehouse()
    {
        if(auth()->user())
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            if ($warehouse) {
                $item_warehouse = $this->warehouses->where('warehouse_id',$warehouse->id)->first();
                return ($item_warehouse) ? $item_warehouse->stock : 0;
            }
        }

        return 0;
    }

    public function warehouses()
    {
        return $this->hasMany(ItemWarehouse::class)->with('warehouse');
    }


    public function item_unit_types()
    {
        return $this->hasMany(ItemUnitType::class);
    }

    public function tags()
    {
        return $this->hasMany(ItemTag::class);
    }

    public function sets()
    {
    return $this->hasMany(ItemSet::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function item_lots()
    {
        return $this->hasMany(ItemLot::class, 'item_id');
    }

    public function lots()
    {
        return $this->morphMany(ItemLot::class, 'item_loteable');
    }

    public  function images()
    {
        return $this->hasMany(ItemImage::class, 'item_id');
    }

    public function lots_group()
    {
        return $this->hasMany(ItemLotsGroup::class, 'item_id');
    }


    public function scopeWhereNotService($query)
    {
        return $query->where('unit_type_id','!=', 'ZZ');
    }

    public  function document_items()
    {
        return $this->hasMany(DocumentItem::class, 'item_id');
    }

    public  function sale_note_items()
    {
        return $this->hasMany(SaleNoteItem::class, 'item_id');
    }

    public function scopeWhereFilterValuedKardex($query, $params)
    {

        if($params->establishment_id){

            return $query->with(['document_items'=> function($q) use($params){
                        $q->whereHas('document', function($q) use($params){
                            $q->whereStateTypeAccepted()
                                ->whereTypeUser()
                                ->whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                                ->where('establishment_id', $params->establishment_id);
                        });
                    },
                    'sale_note_items' => function($q) use($params){
                        $q->whereHas('sale_note', function($q) use($params){
                            $q->whereStateTypeAccepted()
                                ->whereNotChanged()
                                ->whereTypeUser()
                                ->whereBetween('date_of_issue', [$params->date_start, $params->date_end])
                                ->where('establishment_id', $params->establishment_id);
                        });
                    }]);

        }

        return $query->with(['document_items'=> function($q) use($params){
                    $q->whereHas('document', function($q) use($params){
                        $q->whereStateTypeAccepted()
                            ->whereTypeUser()
                            ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                    });
                },
                'sale_note_items' => function($q) use($params){
                    $q->whereHas('sale_note', function($q) use($params){
                        $q->whereStateTypeAccepted()
                            ->whereNotChanged()
                            ->whereTypeUser()
                            ->whereBetween('date_of_issue', [$params->date_start, $params->date_end]);
                    });
                }]);
    }

    public function scopeWhereIsNotActive($query)
    {
        return $query->where('active', false);
    }

    public function scopeWhereHasInternalId($query)
    {
        return $query->where('internal_id','!=', null);
    }

    public function getRowSearchResource($warehouse = null)
    {
        $detail = $this->getFullDescription();
        $warehouse = $warehouse ?? Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'full_description' => $detail['full_description'],
            'brand' => $detail['brand'],
            'category' => $detail['category'],
            'brand_id' => $this->brand_id,
            'model' => $this->model,
            'internal_id' => $this->internal_id,
            'description' => $this->description,
            'currency_type_id' => $this->currency_type_id,
            'currency_type_symbol' => $this->currency_type->symbol,
            'sale_unit_price' => round($this->sale_unit_price, 2),
            'purchase_unit_price' => $this->purchase_unit_price,
            'unit_type_id' => $this->unit_type_id,
            'calculate_quantity' => (bool) $this->calculate_quantity,
            'item_unit_types' => collect($this->item_unit_types)->transform(function($row) {
                return [
                    'id' => $row->id,
                    'description' => "{$row->description}",
                    'item_id' => $row->item_id,
                    'unit_type_id' => $row->unit_type_id,
                    'unit_type' => $row->unit_type,
                    'quantity_unit' => $row->quantity_unit,
                    'price1' => $row->price1,
                    'price2' => $row->price2,
                    'price3' => $row->price3,
                    'price_default' => $row->price_default,
                ];
            }),
            'warehouses' => collect($this->warehouses)->transform(function($row) use($warehouse){
                return [
                    'warehouse_description' => $row->warehouse->description,
                    'stock' => $row->stock,
                    'warehouse_id' => $row->warehouse_id,
                    'checked' => ($row->warehouse_id == $warehouse->id) ? true : false,
                ];
            }),
            'lots_group' => collect($this->lots_group)->transform(function($row){
                return [
                    'id'  => $row->id,
                    'code' => $row->code,
                    'quantity' => $row->quantity,
                    'date_of_due' => $row->date_of_due,
                    'checked'  => false
                ];
            }),
            'lots' => $this->item_lots->where('has_sale', false)->where('warehouse_id', $warehouse->id)->transform(function($row) {
                return [
                    'id' => $row->id,
                    'series' => $row->series,
                    'date' => $row->date,
                    'item_id' => $row->item_id,
                    'warehouse_id' => $row->warehouse_id,
                    'has_sale' => (bool)$row->has_sale,
                    'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code:null):null
                ];
            }),
            'lots_enabled' => (bool) $this->lots_enabled,
            'series_enabled' => (bool) $this->series_enabled,
            'unit_type' => $this->unit_type,
            'tax' => $this->tax,
            'is_set' => (bool) $this->is_set,
            
        ];
    }

        
    /**
     * Descripción para busqueda de items
     *
     * @param $row
     * @param $warehouse
     * @return void
     */
    public function getFullDescription(){

        $desc = ($this->internal_id)?$this->internal_id.' - '.$this->name : $this->name;
        $category = ($this->category) ? "{$this->category->name}" : "";
        $brand = ($this->brand) ? "{$this->brand->name}" : "";

        $desc = "{$desc} - {$brand}";

        return [
            'full_description' => $desc,
            'brand' => $brand,
            'category' => $category,
        ];
    }

    
    /**
     * 
     * Filtros para busqueda de productos
     * Usado en:
     * ItemController
     *
     * @param $query
     * @param $input
     */
    public function scopeWhereFilterSearchItem($query, $input)
    {
        return $query->where('name','like', "%{$input}%")
                    ->orWhere('internal_id','like', "%{$input}%")
                    ->orderBy('name');
    }
    
    public function scopeWhereFilterAllowedItem($query)
    {
        return $query->whereWarehouse()
                        ->whereNotIsSet()
                        ->whereIsActive()
                        ->whereNotItemsAiu();
    }

    
    /**
     * 
     * Filtros opcionales para componente de busqueda
     *
     * @param  Builder $query
     * @param  bool $filter_warehouse
     * @return Builder
     */
    public function scopeOptionalFiltersSearchData($query, $filter_warehouse)
    {
        if($filter_warehouse) $query->whereWarehouse();

        return $query->whereNotIsSet()
                        ->whereIsActive()
                        ->whereNotItemsAiu();
    }


}
