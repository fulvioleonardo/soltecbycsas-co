<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;

class QuotationRequest extends FormRequest
{
    use RequestsTrait;
    
    /**
     * Form
     * @var string
     */
    public $form = 'document';
    
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
            'client_id' => 'required|exists:tenant.clients,id',
            'currency_id' => 'required|exists:tenant.currencies,id',
            'observation' => 'nullable|string|max:255',
            'sale' => 'required|numeric|between:0.00,9999999999.99',
            'total_discount' => 'required|numeric|between:0.00,9999999999.99',
            'taxes' => 'nullable|array',
            'taxes.*.' => 'nullable|exists:tenant.taxes,id',
            'total_tax' => 'required|numeric|between:0.00,9999999999.99',
            'subtotal' => 'required|numeric|between:0.00,9999999999.99',
            'total' => 'required|numeric|between:0.00,9999999999.99',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:tenant.items,id'
        ];
    }
}
