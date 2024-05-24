<?php

namespace Modules\Factcolombia1\Models\SystemService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;



class TypeCurrency extends Model
{
  
    use UsesSystemConnection;

    protected $table = 'co_service_type_currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code',
    ];
}
