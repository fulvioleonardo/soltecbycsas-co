<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
     

    public function toArray($request) {
        

        return $this->collection->transform(function($row, $key){ 
             
            return [
                'id' => $row->id, 
                'type_person_id' => $row->type_person_id, 
                'name' => $row->name, 
                'type_identity_document_id' => $row->type_identity_document_id, 
                'type_identity_document_name' => $row->typeIdentityDocument->name, 
                'identification_number' => $row->identification_number, 
                'email' => $row->email, 

            ];
        });
    }
}
