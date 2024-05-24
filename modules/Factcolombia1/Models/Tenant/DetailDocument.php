<?php

namespace Modules\Factcolombia1\Models\Tenant;

use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class DetailDocument extends Model
{
    use SoftDeletes, HasJsonRelationships, UsesTenantConnection;

    protected $table = 'co_detail_documents';
    
    
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
    protected $fillable = ['document_id', 'item_id', 'item', 'type_unit_id', 'quantity', 'price', 'tax_id', 'tax', 'total_tax', 'subtotal', 'discount', 'total'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Get the item collect
     *
     * @return string
     */
    public function getItemCollectAttribute() {
        return collect($this->item);
    }
}
