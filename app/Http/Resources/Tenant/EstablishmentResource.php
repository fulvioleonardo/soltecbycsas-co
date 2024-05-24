<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class EstablishmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'country_id' => $this->country_id,
            'department_id' => $this->department_id,
            'city_id' => $this->city_id,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'code' => $this->code,
            'trade_address' => $this->trade_address,
            'web_address' => $this->web_address,
            'establishment_logo' => $this->establishment_logo,
            'aditional_information' => $this->aditional_information,
        ];
    }
}
