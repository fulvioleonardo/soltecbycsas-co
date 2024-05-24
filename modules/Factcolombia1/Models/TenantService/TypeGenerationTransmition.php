<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TypeGenerationTransmition extends Model
{
    use  UsesTenantConnection;

    protected $table = 'co_type_generation_transmitions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code',
    ];

        
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
        ];

    }

}
