<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;

class TaxUpdateRequest extends FormRequest
{
    use RequestsTrait;

    /**
     * Form
     * @var string
     */
    public $form = 'tax';

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
        return [
            'name' => "required|max:30|unique:tenant.co_taxes,name,{$this->id}",
            'code' => 'nullable|max:2',
            'rate' => 'nullable|numeric|between:0.00,9999999999.99',
            'conversion' => 'required|numeric|between:0.00,9999.99',
            'is_percentage' => 'required|boolean',
            'is_fixed_value' => 'required|boolean',
            'is_retention' => 'required|boolean',
            'in_base' => 'required|boolean',
            'in_tax' => 'nullable|exists:tenant.co_taxes,id'
        ];
    }
}
