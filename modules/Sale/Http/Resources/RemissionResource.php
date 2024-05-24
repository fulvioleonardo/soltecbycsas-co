<?php

namespace Modules\Sale\Http\Resources;

use Modules\Sale\Models\SaleOpportunity;
use Illuminate\Http\Resources\Json\JsonResource;

class RemissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getRowResource();
    }
}
