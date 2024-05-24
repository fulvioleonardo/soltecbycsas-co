<?php

namespace Modules\Factcolombia1\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
{
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
            'password' => 'nullable|confirmed|max:20',
            'limit_documents' => 'required|numeric|between:0,99999999999',
            'type_document_identification_id' => 'required',
            'type_organization_id' => 'required',
            'type_regime_id' => 'required',
            'department_id'=> 'required',
            'municipality_id' => 'required',
            'merchant_registration'=> 'required',
            'address'=> 'required|string',
            'phone'=> 'required|numeric|digits_between:7,10',
            'dv' => 'required|numeric|digits_between:1,9|max:9',
            'ica_rate'=> 'required',
            'economic_activity_code'=> 'required',
        ];
    }
}
