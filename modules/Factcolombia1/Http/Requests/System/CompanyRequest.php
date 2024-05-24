<?php

namespace Modules\Factcolombia1\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'identification_number' => 'required|numeric|digits_between:1,15|unique:co_companies,identification_number',
            'name' => 'required|max:50',
            'email' => 'required|email|max:50|unique:co_companies,email',
            // 'subdomain' => 'required|alpha|max:10|unique:co_companies,subdomain',
            'subdomain' => 'required|max:10|unique:co_companies,subdomain',
            'password' => 'required|confirmed|max:20',
            'limit_documents' => 'required|numeric|between:0,99999999999',
            'limit_users' => 'required|numeric|between:0,99999999999',
            //'language_id' => 'required',
            //'tax_id' => 'required',
          //  'type_environment_id'  => 'required',
           // 'type_operation_id' => 'required',
            'type_document_identification_id' => 'required',
           // 'country_id' => 'required',
           // 'type_currency_id' => 'required',
            'type_organization_id' => 'required',
            'type_regime_id' => 'required',
           // 'type_liability_id' => 'required',
            'department_id'=> 'required',
            'municipality_id' => 'required',
            'merchant_registration'=> 'required',
            'address'=> 'required|string',
            'phone'=> 'required|numeric|digits_between:7,10',
            'dv' => 'required|numeric|digits_between:1,9|max:9',
            'ica_rate'=> 'required',
            'economic_activity_code'=> 'required',
            'api_token' => 'nullable|string',
        ];
    }
}
