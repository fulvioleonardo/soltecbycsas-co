<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Modules\Factcolombia1\Models\TenantService\{
    Tax as TypeTax
};

class Tax extends Model
{
    use SoftDeletes, UsesTenantConnection;
    protected $table = 'co_taxes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'code', 'rate', 'conversion', 'is_percentage', 'is_fixed_value', 'is_retention', 'in_base', 'in_tax', 'type_tax_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_fixed_value' => 'boolean',
        'is_percentage' => 'boolean',
        'is_retention' => 'boolean',
        'in_base' => 'boolean'
    ];

    protected $with = ['type_tax'];

    /**
    * Get the tax belongs to
    */
    public function tax() {
        return $this->belongsTo(Tax::class, 'in_tax');
    }

    public function type_tax() {
        return $this->belongsTo(TypeTax::class, 'type_tax_id');
    }

    public function getSearchRowResource()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'rate' =>  $this->rate,
            'conversion' =>  $this->conversion,
            'is_percentage' =>  $this->is_percentage,
            'is_fixed_value' =>  $this->is_fixed_value,
            'is_retention' =>  $this->is_retention,
            'in_base' =>  $this->in_base,
            'in_tax' =>  $this->in_tax,
            'type_tax_id' =>  $this->type_tax_id,
            'type_tax' =>  $this->type_tax,
            'retention' =>  0,
            'total' =>  0,
        ];
    }
    
}
