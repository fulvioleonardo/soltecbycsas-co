<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
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
                'external_id' => $row->external_id,
                'number_document' => $row->number_document,
                'order_id' => str_pad($row->id, 6, "0", STR_PAD_LEFT),
                'customer_name' => $row->customer->name,
                'customer_email' => $row->customer->email,
                'customer_telefono' => $row->customer->telephone,
                'customer_direccion' => $row->customer->address,
                'items' => $row->items,
                'total' => $row->total,
                'payment_method_description' => strtoupper($row->getPaymentMethodDescription()),
                // 'reference_payment' => strtoupper($row->reference_payment),
                'document_external_id' => $row->document_external_id,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'status_order_id' => $row->status_order_id,
                'customer' => $row->customer
            ];
        });
    }
}
