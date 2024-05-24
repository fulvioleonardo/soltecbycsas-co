<?php
namespace Modules\Factcolombia1\Models\SystemService;

use Hyn\Tenancy\Traits\UsesSystemConnection;

use Illuminate\Database\Eloquent\Model;


class Country extends Model
{
    
    use UsesSystemConnection;
    protected $table = 'co_service_countries';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code',
    ];
}
