<?php

namespace Modules\Purchase\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportDocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->getRowResource();
    }
}
