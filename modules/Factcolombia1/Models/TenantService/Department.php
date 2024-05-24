<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

/**
 * @property integer $id
 * @property integer $country_id
 * @property string $name
 * @property string $code
 * @property string $created_at
 * @property string $updated_at
 * @property Country $country
 * @property Municipality[] $municipalities
 */
class Department extends Model
{
    use  UsesTenantConnection;
    protected $table = 'co_service_departments';
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'name', 'code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'country_id',
    ];
}
