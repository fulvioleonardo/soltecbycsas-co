<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TypeOvertimeSurcharge extends Model
{
    use  UsesTenantConnection;

    protected $table = 'co_type_overtime_surcharges';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'percentage',
    ];

    public function getTypeAttribute($value)
    {
        return trim($value);
    }
        
    /**
     * Use in resource and collection
     *
     * @return array
     */
    public function getRowResource(){

        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'percentage' => $this->percentage,
            'type' => $this->type,
        ];

    }

}
