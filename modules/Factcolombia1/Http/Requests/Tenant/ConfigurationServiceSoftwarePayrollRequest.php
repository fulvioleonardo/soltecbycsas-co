<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationServiceSoftwarePayrollRequest extends FormRequest
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

            'idpayroll' => 'required|string',
            'pinpayroll' => 'required|numeric|digits:5',
            'test_set_id_payroll' => 'required|string',
            
        ];
    }
}
