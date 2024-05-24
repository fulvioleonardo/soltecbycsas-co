<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PersonCollection extends ResourceCollection
{


    public function toArray($request) {


        return $this->collection->transform(function($row, $key){

            return [
                'id' => $row->id,
                'description' => $row->number.' - '.$row->name,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_id' => $row->identity_document_type_id,
                'address' =>  $row->address,
                'email' =>  $row->email,
                'telephone' =>  $row->telephone,
                'type_person_id' => $row->type_person_id,
                'type_regime_id' => $row->type_regime_id,
                'city_id' => $row->city_id,
                'type_obligation_id' => $row->type_obligation_id,
                'dv' => $row->dv
            ];
        });
    }
}
