<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class DetailQuotation extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_detail_quotations';
    
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'item' => 'object',
        'tax' => 'object'
    ];
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quotation_id', 'item_id', 'item', 'type_unit_id', 'quantity', 'price', 'tax_id', 'tax', 'total_tax', 'subtotal', 'discount', 'total'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
