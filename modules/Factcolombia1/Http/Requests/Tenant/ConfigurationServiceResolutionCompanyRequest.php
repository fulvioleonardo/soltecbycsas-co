<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ConfigurationServiceResolutionCompanyRequest extends FormRequest
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
            'type_document_id'  => 'required',
            'code'  => 'required',
            'name'  => 'required',
            'prefix'            => 'nullable|string|max:4',
            'resolution'        => 'nullable|required_if:type_document_id,=,1|required_if:type_document_id,=,9,10,11|string',
            'resolution_date'   => 'nullable|required_if:type_document_id,=,1|date_format:Y-m-d',
            'technical_key'     => 'nullable|required_if:type_document_id,=,1|string',
            'from'              => 'required|integer',
            'to'                => 'required|integer|min:'.((int) ($this->from + 1)),
            'date_from'         => 'nullable|required_if:type_document_id,=,1|date_format:Y-m-d',
            'date_to'           => 'nullable|required_if:type_document_id,=,1|date_format:Y-m-d|after:date_from',
            'description'       => 'nullable|string',
        ];
    }
}
