<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TypeDocument extends Model
{
    use  UsesTenantConnection;

    protected $table = 'co_service_type_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'code',
        'cufe_algorithm',
        'prefix',
    ];

}
