<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use Illuminate\Support\Facades\DB;
use Modules\Factcolombia1\Models\Tenant\TypeUnit;

class SaleNoteItem extends ModelTenant
{
    // protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'sale_note_id',
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

        'inventory_kardex_id',

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

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }
    
    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    
    public function scopeWhereDefaultDocumentType($query, $params)
    {
        
        $db_raw =  DB::raw("sale_note_items.id as id, sale_notes.series as series, sale_notes.number as number,
                            sale_note_items.item as item, sale_note_items.quantity as quantity, sale_notes.date_of_issue as date_of_issue");

        if($params['person_id']){

            return $query->whereHas('sale_note', function($q) use($params){
                            $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']])
                                ->where('customer_id', $params['person_id'])
                                ->whereTypeUser();
                        })
                        ->join('sale_notes', 'sale_note_items.sale_note_id', '=', 'sale_notes.id')
                        ->select($db_raw)
                        ->latest('id');
                        
        }

        
        return $query->whereHas('sale_note', function($q) use($params){
                    $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']])
                        ->where('user_id', $params['seller_id'])
                        ->whereTypeUser();
                })
                ->join('sale_notes', 'sale_note_items.sale_note_id', '=', 'sale_notes.id')
                ->select($db_raw)
                ->latest('id');

    }

}