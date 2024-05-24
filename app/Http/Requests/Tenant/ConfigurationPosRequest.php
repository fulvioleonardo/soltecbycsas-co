<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConfigurationPosRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'prefix' => ['required'],
            'resolution_number' => ['required'],
            'resolution_date' => ['required'],
            'date_from' => ['required'],
            'date_end' => ['required'],
            'from' => ['required'],
            'to' => ['required'],
            'electronic' => ['nullable'],
            'plate_number' => ['nullable', 'required_if:electronic,true'],
            'cash_type' => ['nullable', 'required_if:electronic,true'],
        ];
    }
}
