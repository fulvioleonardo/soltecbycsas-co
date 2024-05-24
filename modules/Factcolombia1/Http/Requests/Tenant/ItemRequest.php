<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
{
    use RequestsTrait;

    /**
     * Form
     * @var string
     */
    public $form = 'item';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $id = $this->input('id');
        return [
            'name' => [
                // 'required|unique:tenant.co_items,name|max:50'
                'required',
                Rule::unique('tenant.co_items')->ignore($id),
            ],
            'code' => [
                // 'required|unique:tenant.co_items,code|alpha_dash|max:11',
                'required',
                Rule::unique('tenant.co_items')->ignore($id),
            ],
            'type_unit_id' => 'required|exists:tenant.co_type_units,id',
            'price' => 'required|numeric|between:0.00,9999999999.99',
            'tax_id' => 'nullable|exists:tenant.co_taxes,id'
        ];
    }
}
