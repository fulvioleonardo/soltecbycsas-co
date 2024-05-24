<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstablishmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->get('id');
        return [
            'description' => [
                'required',
                Rule::unique('tenant.establishments')->ignore($id),
            ],
            'country_id' => [
                'required',
            ],
            'department_id' => [
                'required',
            ],
            'city_id' => [
                'required',
            ], 
            'address' => [
                'required',
            ],
            'email' => [
                'required',
                'email'
            ],
            'telephone' => [
                'required',
            ],
            'code' => [
                'required',
            ],
        ];
    }
}