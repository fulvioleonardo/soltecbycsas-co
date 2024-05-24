<?php

namespace Modules\Payroll\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TypeWorkerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'code' => [
                'required',
                Rule::unique('tenant.co_type_workers')->ignore($id),
            ], 
            'name' => [
                'required',
            ],
            
        ];
        
    }

}