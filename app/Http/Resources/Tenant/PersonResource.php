<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
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
            'type' => $this->type,
            'identity_document_type_id' => $this->identity_document_type_id,
            'number' => $this->number,
            'name' => $this->name,
            'trade_name' => $this->trade_name,
            'country_id' => $this->country_id,
            'department_id' => $this->department_id,
            // 'province_id' => $this->province_id,
            // 'district_id' => $this->district_id,
            'address' => $this->address,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'perception_agent' => (bool) $this->perception_agent,
            'percentage_perception' => $this->percentage_perception,
            'type_obligation_id' => $this->type_obligation_id,
            // 'state' => $this->state,
            // 'condition' => $this->condition,
            'person_type_id' => $this->person_type_id,
            'comment' => $this->comment,
            'addresses' => collect($this->addresses)->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'trade_name' => $row->trade_name,
                    'country_id' => $row->country_id,
                    'location_id' => !is_null($row->location_id)?$row->location_id:[],
                    'address' => $row->address,
                    'phone' => $row->phone,
                    'email' => $row->email,
                    'main' => (bool)$row->main,
                ];
            }),
            'type_person_id' => $this->type_person_id,
            'type_regime_id' => $this->type_regime_id,
            'city_id' => $this->city_id,
            'code' => $this->code,
            'dv' => $this->dv,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'postal_code' => $this->postal_code,

            // 'more_address' =>  $this->more_address,
        ];
    }
}
