<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Configuration;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PosCollection extends ResourceCollection
{

    protected $configuration;

    public function __construct($resource, $configuration)
    {
        parent::__construct($resource);
        $this->configuration = $configuration;
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {

            $full_description = ($row->internal_id)?$row->internal_id.' - '.$row->name:$row->name;
            $price_with_tax = $this->getSaleUnitPriceWithTax($row, $this->configuration->decimal_quantity);
            return [
                'id' => $row->id,
                'item_id' => $row->id,
                'full_description' => $full_description,
                'description' => $row->description,
                'name' => $row->name,
                'currency_type_id' => $row->currency_type->id,
                'category_id' => $row->category_id,
                'internal_id' => $row->internal_id,
                'currency_type_symbol' => $row->currency_type->symbol,
                'sale_unit_price' => number_format($row->sale_unit_price, $this->configuration->decimal_quantity, ".",""),
                'purchase_unit_price' => $row->purchase_unit_price,
                'unit_type_id' => $row->unit_type_id,
                'calculate_quantity' => (bool) $row->calculate_quantity,
                'is_set' => (bool) $row->is_set,
                'tax_id' => $row->tax_id,
                'edit_unit_price' => false,
                'aux_quantity' => 1,
                'aux_sale_unit_price' => number_format($row->sale_unit_price, $this->configuration->decimal_quantity, ".",""),
                'edit_sale_unit_price' => $price_with_tax,
                'image_url' => ($row->image !== 'imagen-no-disponible.jpg') ? asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'items'.DIRECTORY_SEPARATOR.$row->image) : asset("/logo/{$row->image}"),
                'sets' => collect($row->sets)->transform(function($r){
                    return [
                        $r->individual_item->name
                    ];
                }),
                'warehouses' => collect($row->warehouses)->transform(function ($row) {
                    return [
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                    ];
                }),
                'item_unit_types' => $row->item_unit_types->transform(function($row) { return $row->getSearchRowResource();}),
                'unit_type' => $row->unit_type,
                'tax' => $row->tax,
                'sale_unit_price_with_tax' => $price_with_tax
            ];
        });
    }

    /**
     * Retorna el precio de venta mas impuesto asignado al producto
     *
     * @param  Item $item
     * @param  $decimal_quantity
     * @return double
     */
    private function getSaleUnitPriceWithTax($item, $decimal_quantity)
    {
        return number_format($item->sale_unit_price * ( 1 + ($item->tax->rate ?? 0) / ($item->tax->conversion ?? 1)), $decimal_quantity, ".","");
    }
}
