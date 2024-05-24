<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TaxCollection extends ResourceCollection
{
    public function toArray($request) {

        return $this->collection->transform(function($row, $key){
            return [
                'id' => $row->id,
                'name' => $row->name,
                'code' => $row->code,
                'rate' => $row->rate,
                'conversion' => $row->conversion,
                'is_percentaje' => $row->is_percentaje,
                'is_fixed_value' => $row->is_fixed_value,
                'is_retention' => $row->is_retention,
                'in_base' => $row->in_base,
                'in_tax' => $row->in_tax,
                'type_tax_id_name' => $row->type_tax->name,

            ];
        });
    }
}
