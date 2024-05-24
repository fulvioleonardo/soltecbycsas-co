<?php

namespace Modules\Factcolombia1\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ItemApiCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return [
                'id' => $row->id,
                'amount_sale_unit_price' => $row->sale_unit_price,
                'apply_store' => (bool)$row->apply_store,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'created_at' => ($row->created_at) ? $row->created_at->format('Y-m-d H:i:s') : '',
                'description' => $row->description,
                'image_url_medium' => ($row->image_medium !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image_medium) : asset("/logo/{$row->image_medium}"),
                'image_url_small' => ($row->image_small !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image_small) : asset("/logo/{$row->image_small}"),
                'image_url' => ($row->image !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image) : asset("/logo/{$row->image}"),
                'internal_id' => $row->internal_id,
                'name' => $row->name,
                'purchase_unit_price' => "{$row->purchase_unit_price}",
                'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                'sale_unit_price' => "{$row->sale_unit_price}",
                'second_name' => $row->second_name,
                'stock_min' => $row->stock_min,
                'stock' => $row->getStockByWarehouse(),
                'tags_id' => $row->tags->pluck('tag_id'),
                'tags' => $row->tags,
                'tax_id' => $row->tax_id,
                'tax' => $row->tax,
                'unit_type_id' => $row->unit_type->name,
                'unit_type' => $row->unit_type,
                'updated_at' => ($row->created_at) ? $row->updated_at->format('Y-m-d H:i:s') : '',
                'warehouse_id' => $row->warehouse_id,
                // 'currency_type_id' => $row->currency_type_id,
                // 'currency_type_symbol' => $row->currency_type->symbol,
                // 'has_igv_description' => $has_igv_description,
                // 'has_igv' => (bool) $row->has_igv,
                // 'item_code_gs1' => $row->item_code_gs1,
                // 'item_code' => $row->item_code,
                'warehouses' => collect($row->warehouses)->transform(function($row) {
                    return [
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                    ];
                }),
            ];
        });
    }
}