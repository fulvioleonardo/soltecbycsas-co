<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PayrollPeriod extends Model
{
    use  UsesTenantConnection;

    protected $table = 'co_payroll_periods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code',
    ];
    
    public function getCodeAttribute($value)
    {
        return trim($value);
    }

}
