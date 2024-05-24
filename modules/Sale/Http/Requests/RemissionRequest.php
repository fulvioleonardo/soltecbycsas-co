<?php

namespace Modules\Sale\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemissionRequest extends FormRequest
{
     
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    { 
        
        return [
            'customer_id' => [
                'required',
            ],
            
            'date_of_issue' => [
                'required',
            ], 

            'currency_id' => 'required|exists:tenant.co_currencies,id',
            'date_expiration' => 'nullable|date',
            'observation' => 'nullable|string|max:255',

            'sale' => 'required|numeric|between:0.00,9999999999.99',
            'total_discount' => 'required|numeric|between:0.00,9999999999.99',
            'taxes' => 'nullable|array',
            'taxes.*.' => 'nullable|exists:tenant.co_taxes,id',
            'total_tax' => 'required|numeric|between:0.00,9999999999.99',
            'subtotal' => 'required|numeric|between:0.00,9999999999.99',
            'total' => 'required|numeric|between:0.00,9999999999.99',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:tenant.items,id',
            'payment_form_id' => 'required',
            'payment_method_id' => 'required',
            
        ];
    }
}
