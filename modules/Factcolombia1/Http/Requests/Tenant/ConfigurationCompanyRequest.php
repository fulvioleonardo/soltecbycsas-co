<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;

class ConfigurationCompanyRequest extends FormRequest
{
    use RequestsTrait;

    /**
     * Form
     * @var string
     */
    public $form = 'company';

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
            'type_identity_document_id' => 'required|exists:tenant.type_identity_documents,id',
            'short_name' => 'required|string|max:15',
            'email' => 'required|email|max:50',
            'country_id' => 'required|exists:tenant.countries,id',
            'department_id' => 'required|exists:tenant.departments,id',
            'city_id' => 'required|exists:tenant.cities,id',
            'address' => 'required|max:50',
            'phone' => 'required|integer|digits_between:7,10',
            'currency_id' => 'required|exists:tenant.currencies,id',
            'type_regime_id' => 'required|exists:tenant.type_regimes,id',
            'economic_activity_code' => 'required|string|max:10',
            'ica_rate' => 'required|string|max:15',
            'type_obligation_id' => 'required|exists:tenant.type_obligations,id',
            'version_ubl_id' => 'required|exists:tenant.version_ubls,id',
            'ambient_id' => 'required|exists:tenant.ambients,id',
            'software_identifier' => 'required|alpha_dash|max:50',
            'software_password' => 'required|alpha_dash|max:10',
            'pin' => 'required|string|max:20',
            'certificate' => 'nullable|file',
            'certificate_name' => 'required|string|max:20',
            'certificate_password' => 'required|string|max:20',
            'certificate_date_end' => 'required|date',
        ];
    }
}
