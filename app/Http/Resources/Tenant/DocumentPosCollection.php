<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentPosCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {

            $total_paid = number_format($row->payments->sum('payment'), 2, ".", "");
            $total_pending_paid = number_format($row->total - $total_paid, 2, ".", "");

            //$btn_generate = (count($row->documents) > 0)?false:true;
            //$btn_payments = (count($row->documents) > 0)?false:true;

            return [
                'id' => $row->id,
                'soap_type_id' => $row->soap_type_id,
                'external_id' => $row->external_id,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'identifier' => $row->identifier,
                'full_number' => $row->series.'-'.$row->number,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number,
                'currency_type_id' => $row->currency->name,
                'total' => number_format($row->total,2),
                'state_type_id' => $row->state_type_id,
                'state_type_description' => $row->state_type->description,
                'changed' => (boolean) $row->changed,
                'enabled_concurrency' => (boolean) $row->enabled_concurrency,
                'quantity_period' => $row->quantity_period,
                'type_period' => $row->type_period,
                'apply_concurrency' => (boolean) $row->apply_concurrency,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                'paid' => (bool)$row->paid,
                'license_plate' => $row->license_plate,
                'total_paid' => $total_paid,
                'total_pending_paid' => $total_pending_paid,
                'user_name' => ($row->user) ? $row->user->name : '',
                'quotation_number_full' => ($row->quotation) ? $row->quotation->number_full : '',
                'sale_opportunity_number_full' => isset($row->quotation->sale_opportunity) ? $row->quotation->sale_opportunity->number_full : '',
                'number_full' => $row->number_full,
                'electronic' => $row->electronic,
                'plate_number' => $row->plate_number,
                'cash_type' => $row->cash_type,
            ];
        });
    }

}
