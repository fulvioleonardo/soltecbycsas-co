<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemCollection extends ResourceCollection
{
     

    public function toArray($request) {
        

        return $this->collection->transform(function($row, $key){ 
             
               
            return [
                'id' => $row->id,
                'date_of_issue' => $row->document->date_of_issue->format('Y-m-d'),
                'customer_name' => $row->document->customer->name,
                'customer_number' => $row->document->customer->number,
                'series' => $row->document->series,
                'alone_number' => $row->document->number,
                'quantity' => number_format($row->quantity,2),
                'total' => number_format($row->total,2),
                'document_type_description' => $row->document->type_document->name,
                'document_type_id' => $row->document->document_type->id,   
 

            ];
        });
    }
}
