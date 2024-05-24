<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Modules\Factcolombia1\Models\Tenant\TypeUnit;
use Modules\Factcolombia1\Models\Tenant\Tax;

class Item extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_items';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'type_unit_id', 'price', 'tax_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
    * Get the type type unit belongs to
    */
    public function typeUnit() {
        return $this->belongsTo(TypeUnit::class);
    }

    /**
    * Get the tax belongs to
    */
    public function tax() {
        return $this->belongsTo(Tax::class);
    }
}
