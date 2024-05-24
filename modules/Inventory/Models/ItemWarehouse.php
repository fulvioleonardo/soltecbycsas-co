<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;

class ItemWarehouse extends ModelTenant
{ 
    protected $table = 'item_warehouse';

    protected $fillable = [
        'item_id',
        'warehouse_id', 
        'stock', 
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    
    public function getGlobalPurchaseUnitPrice()
    {
        return number_format($this->item->purchase_unit_price * $this->stock, 6, ".", "");
    }
    
    public function getGlobalSaleUnitPrice()
    {
        return number_format($this->item->sale_unit_price * $this->stock, 6, ".", "");
    }

}