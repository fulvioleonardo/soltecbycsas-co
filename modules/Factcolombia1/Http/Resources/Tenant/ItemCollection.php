<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
     

    public function toArray($request) {
        

        return $this->collection->transform(function($row, $key){ 
             
            return [
                'id' => $row->id, 
                'name' => $row->name, 
                'code' => $row->code, 
                'type_unit_id' => $row->type_unit_id, 
                'type_unit_name' => $row->typeUnit->name, 
                'price' => $row->price, 
                'tax_id' => $row->tax_id, 
                'tax_name' => $row->tax->name, 

            ];
        });
    }
}
