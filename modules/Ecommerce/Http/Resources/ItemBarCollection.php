<?php

namespace Modules\Ecommerce\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Controllers\Tenant\Api\ServiceController;

class ItemBarCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {


        return $this->collection->transform(function($row, $key){
 
            $sale_unit_price = $row->sale_unit_price;

            return [
                'id' => $row->id,
                'unit_type_id' => $row->unit_type_id,
                'description' => $row->description,
                'name' => $row->name,
                'second_name' => $row->second_name,
                'warehouse_id' => $row->warehouse_id,
                'internal_id' => $row->internal_id,
                'item_code' => $row->item_code,
                'item_code_gs1' => $row->item_code_gs1,
                'stock' => $row->getStockByWarehouse(),
                'stock_min' => $row->stock_min,
                'currency_type_id' => $row->currency_type_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'amount_sale_unit_price' => $sale_unit_price,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'sale_unit_price' => "$ ".$sale_unit_price,
                'created_at' => ($row->created_at) ? $row->created_at->format('Y-m-d H:i:s') : '',
                'updated_at' => ($row->created_at) ? $row->updated_at->format('Y-m-d H:i:s') : '',
                'warehouses' => collect($row->warehouses)->transform(function($row) {
                    return [
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                    ];
                }),
                'apply_store' => (bool)$row->apply_store,
                'image_url' => ($row->image !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image) : asset("/logo/{$row->image}"),
                'image_url_medium' => ($row->image_medium !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image_medium) : asset("/logo/{$row->image_medium}"),
                'image_url_small' => ($row->image_small !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image_small) : asset("/logo/{$row->image_small}"),
                'tags' => $row->tags,
                'tags_id' => $row->tags->pluck('tag_id'),
                 


            ];
        });
    }

    
    private function getExchangeRateSale(){

        $exchange_rate = app(ServiceController::class)->exchangeRateTest(date('Y-m-d'));

        return (array_key_exists('sale', $exchange_rate)) ? $exchange_rate['sale'] : 1;

    }
}