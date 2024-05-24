<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TypeLawDeductions extends Model
{
    use  UsesTenantConnection;

    protected $table = 'co_type_law_deductions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code',
    ];
    
    /**
     * Filtro para obtener registros para el empleado
     *
     * @param  $query
     */
    public static function scopeWhereTypeLawDeductionsWorker($query)
    {
        return $query->whereIn('id', ['1', '2', '3', '5', '7', '9']);
    }

}
