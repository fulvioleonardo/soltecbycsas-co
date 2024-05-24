<?php

namespace Modules\RadianEvent\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchImapRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search_start_date' => [
                'required',
            ],
            'search_end_date' => [
                'required',
            ],
        ];
    }
}