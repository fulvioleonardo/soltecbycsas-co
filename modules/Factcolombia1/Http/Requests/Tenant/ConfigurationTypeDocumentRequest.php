<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;

class ConfigurationTypeDocumentRequest extends FormRequest
{
    use RequestsTrait;

    /**
     * Form
     * @var string
     */
    public $form = '';

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
        $this->form = "type_documents_{$this->id}";

        return [
            'resolution_number' => 'nullable|numeric|digits_between:1,20',
            'resolution_date' => 'nullable|date',
            'resolution_date_end' => 'nullable|date',
            'technical_key' => 'nullable|alpha_dash|max:80',
            'prefix' => 'nullable|alpha_dash|max:5',
            'from' => 'required|numeric|digits_between:1,15',
            'to' => 'required|numeric|digits_between:1,15',
            'generated' => 'required|numeric|digits_between:1,15',
            'description' => 'nullable|string'
        ];
    }
}
