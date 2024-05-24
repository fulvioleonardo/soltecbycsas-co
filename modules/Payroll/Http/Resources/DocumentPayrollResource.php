<?php

namespace Modules\Payroll\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentPayrollResource extends JsonResource
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
