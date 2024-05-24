<?php

namespace Modules\Payroll\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkerRequest extends FormRequest
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
                Rule::unique('tenant.co_workers')->ignore($id),
            ], 
            'type_worker_id' => [
                'required',
            ],
            'sub_type_worker_id' => [
                'required',
            ],
            'payroll_type_document_identification_id' => [
                'required',
            ],
            'municipality_id' => [
                'required',
            ],
            'type_contract_id' => [
                'required',
            ],
            'identification_number' => [
                'required',
                'alpha_num',
                'digits_between:1,15',
            ],

            'surname' => [
                'required',
            ],
            'second_surname' => [
                'required',
            ],
            'first_name' => [
                'required',
            ],
            'address' => [
                'required',
            ],
            'high_risk_pension' => [
                'required',
            ],
            'integral_salarary' => [
                'required',
            ],
            'salary' => [
                'required',
                'numeric',
            ], 
            'cellphone' => [
                'nullable',
                'numeric',
                'integer',
                'digits_between:7,11',
            ], 
            'email' => [
                'nullable',
                'email',
            ], 
            'work_start_date' => [
                'required',
            ], 
            'payroll_period_id' => [
                'required',
            ], 
            
            // Payment
            'payment' => 'required|array',
            'payment.payment_method_id' => 'required',
            'payment.bank_name' => 'nullable|required_if:payment.payment_method_id,2,3,4,5,6,7,21,22,30,31,42,45,46,47,|string',
            'payment.account_type' => 'nullable|required_if:payment.payment_method_id,2,3,4,5,6,7,21,22,30,31,42,45,46,47,|string',
            'payment.account_number' => 'nullable|required_if:payment.payment_method_id,2,3,4,5,6,7,21,22,30,31,42,45,46,47,|string',

            
        ];
    }

}