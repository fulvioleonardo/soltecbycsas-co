<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;

class ClientRequest extends FormRequest
{
    use RequestsTrait;

    /**
     * Form
     * @var string
     */
    public $form = 'client';

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
            'type_person_id' => 'nullable|exists:tenant.co_type_people,id',
            'type_regime_id' => 'nullable|exists:tenant.co_type_regimes,id',
            'type_identity_document_id' => 'required|exists:tenant.co_type_identity_documents,id',
            'type_obligation_id' => 'required|exists:tenant.co_type_obligations,id',
            'identification_number' => "required|numeric|digits_between:1,15|unique:tenant.co_clients,identification_number,null,null,type_identity_document_id,{$this->type_identity_document_id}",
            'name' => 'required|max:50',
            'country_id' => 'nullable|exists:tenant.co_countries,id',
            'department_id' => 'nullable|exists:tenant.co_departments,id',
            'city_id' => 'nullable|exists:tenant.co_cities,id',
            'address' => 'nullable|max:50',
            'phone' => 'nullable|min:7|max:10',
            'email' => 'nullable|email|max:50',
            'code' => 'required|unique:tenant.co_clients,code|alpha_dash|max:11',
            'dv' => 'required|max:1',
        ];
    }
}
