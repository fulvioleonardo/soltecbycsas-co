<?php

namespace Modules\Factcolombia1\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Factcolombia1\Traits\Tenant\RequestsTrait;

class ReportTaxRequest extends FormRequest
{
    use RequestsTrait;
    
    /**
     * Form
     * @var string
     */
    public $form = 'report_tax';
    
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
            'date_from' => 'required|date',
            'date_up' => 'required|date|after_or_equal:date_from'
        ];
    }
}
