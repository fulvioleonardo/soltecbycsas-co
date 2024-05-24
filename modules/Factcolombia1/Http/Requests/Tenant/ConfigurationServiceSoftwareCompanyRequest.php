<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationServiceSoftwareCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
    
        return [

            'id_software' => 'required|string',
            'pin_software' => 'required|numeric|digits:5',
            //'url_software' => 'nullable|string|url',
            'test_id' => 'required'
        ];
    }
}
