<?php

namespace Modules\Purchase\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupportDocumentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => [
                'required',
            ],
            'type_document_id' => [
                'required',
            ],
            'prefix' => [
                'required',
            ],
            'date_of_issue' => [
                'required',
            ],
        ];
    }
}
