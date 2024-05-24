<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'internal_id' => [
                'required',
                Rule::unique('tenant.items')->ignore($id),
            ],
            // 'description' => [
            //     'required',
            // ],
            // 'name' => [
            //     'required',
            // ],
            // 'second_name' => [
            //     'required',
            // ],
            'name' => [
                // 'required|unique:tenant.co_items,name|max:50'
                'required',
                Rule::unique('tenant.items')->ignore($id),
            ],
            'unit_type_id' => [
                'required',
            ],
            'currency_type_id' => [
                'required'
            ],
            'sale_unit_price' => [
                'required',
                'numeric',
                'gt:0'
            ],
            'purchase_unit_price' => [
                'required', 'numeric'
            ],
            'stock' => [
                'required',
                // 'gt:0'
            ],
            'stock_min' => [
                'required',
                // 'gt:0'
            ],
            // 'sale_affectation_igv_type_id' => [
            //     'required'
            // ],
            // 'purchase_affectation_igv_type_id' => [
            //     'required'
            // ],
            // 'category_id' => [
            //     'required_if:is_set,false',
            // ],
            // 'brand_id' => [
            //     'required_if:is_set,false',
            // ],
            'tax_id' => 'nullable|exists:tenant.co_taxes,id',
            'purchase_tax_id' => 'required|exists:tenant.co_taxes,id',

        ];
    }

    public function messages()
    {
        return [
            // 'description.required' => 'El campo nombre es obligatorio.',
            'sale_unit_price.gt' => 'El precio unitario de venta debe ser mayor que 0.',
        ];
    }
}
