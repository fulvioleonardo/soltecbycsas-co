<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Purchase;
use Modules\Inventory\Models\Warehouse;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $purchase = Purchase::find($this->id);
        $purchase->purchase_payments = self::getTransformPayments($purchase->purchase_payments);
        $purchase->items = self::getTransformItems($purchase->items);
        $purchase->customer_number = $purchase->customer_id ? $purchase->customer->number:null;
        
        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'group_id' => $this->group_id,
            'number' => $this->number_full,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'purchase' => $purchase
             
        ];
    }

    
    public static function getTransformPayments($payments){
        
        return $payments->transform(function($row, $key){ 
            return [
                'id' => $row->id, 
                'purchase_id' => $row->purchase_id, 
                'date_of_payment' => $row->date_of_payment->format('Y-m-d'), 
                'payment_method_type_id' => $row->payment_method_type_id, 
                'has_card' => $row->has_card, 
                'card_brand_id' => $row->card_brand_id, 
                'reference' => $row->reference, 
                'payment' => $row->payment, 
                'payment_method_type' => $row->payment_method_type, 
                'payment_destination_id' => ($row->global_payment) ? ($row->global_payment->type_record == 'cash' ? 'cash':$row->global_payment->destination_id):null, 
                'payment_filename' => ($row->payment_file) ? $row->payment_file->filename:null, 
            ];
        }); 

    }


    public static function getTransformItems($items){
        
        return $items->transform(function($row, $key){ 
            return [
                'id' => $row->id, 
                'purchase_id' => $row->purchase_id,  
                'item_id' => $row->item_id,  
                'item' => $row->item,  
                'lot_code' => $row->lot_code,  
                'quantity' => $row->quantity,  
                'date_of_due' => $row->date_of_due,  
                'unit_price' => $row->unit_price,  
                'total_discount' => $row->total_discount,  
                'total' => $row->total,  
                'warehouse_id' => $row->warehouse_id,  
                'lots' => $row->lots,  

                'unit_type_id' => $row->unit_type_id,  
                'tax_id' => $row->tax_id,  
                'tax' => $row->tax,  
                'total_tax' => $row->total_tax,  
                'subtotal' => $row->subtotal,  
                'discount' => $row->discount,  

                'warehouse' => ($row->warehouse) ? $row->warehouse :  self::getWarehouse($row->purchase->establishment_id),  
            ];
        }); 

    }

    public static function getWarehouse($establishment_id){
        return Warehouse::where('establishment_id', $establishment_id)->first();
    }

}
