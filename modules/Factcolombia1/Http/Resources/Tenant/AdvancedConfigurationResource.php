<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvancedConfigurationResource extends JsonResource
{
     
    public function toArray($request) 
    {
        return $this->getRowResource();
    }
    
}
