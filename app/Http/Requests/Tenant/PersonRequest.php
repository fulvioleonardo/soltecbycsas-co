<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        $type = $this->input('type');
        return [
            'number' => [
                'required',
                'numeric',
                'digits_between:1,15',
                Rule::unique('tenant.persons')->where(function ($query) use($id, $type) {
                    return $query->where('type', $type)
                                 ->where('id', '<>' ,$id);
                })
            ],
            'name' => [
                'required',
                Rule::unique('tenant.persons')->where(function ($query) use($id, $type) {
                    return $query->where('type', $type)
                                 ->where('id', '<>' ,$id);
                })
            ],
            'identity_document_type_id' => [
                'required',
            ],
            'type_obligation_id' => [
                'required',
            ],
            'country_id' => [
                'required',
            ],
            // 'person_type_id' => [
            //     'required_if:type,"customers"',
            // ],
            'department_id' => [
                'required_if:identity_document_type_id,"066"',
            ],
            // 'province_id' => [
            //     'required_if:identity_document_type_id,"066"',
            // ],
            // 'district_id' => [
            //     'required_if:identity_document_type_id,"066"',
            // ],
            'address' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
            ],
            'telephone' => [
                'required',
                'numeric',
                'integer',
                'digits_between:7,10',
            ],
            'code' => [
                'required',
                Rule::unique('tenant.persons')->where(function ($query) use($id, $type) {
                    return $query->where('type', $type)
                                 ->where('id', '<>' ,$id);
                })
            ],
            // 'code' => 'required|unique:tenant.persons,code|alpha_dash|max:11',
            'dv' => 'required|max:1',
            'postal_code' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}
