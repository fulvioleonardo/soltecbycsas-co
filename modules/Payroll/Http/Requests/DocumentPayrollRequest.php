<?php

namespace Modules\Payroll\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocumentPayrollRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [ 
            'type_document_id' => [
                'required',
            ],
            'prefix' => [
                'required',
            ],
            'payroll_period_id' => [
                'required',
            ],
            'worker_id' => [
                'required',
            ],

            // Period
            'period' => 'required|array',
            'period.admision_date' => 'required|date_format:Y-m-d',
            'period.settlement_start_date' => 'required|date_format:Y-m-d',
            'period.settlement_end_date' => 'required|date_format:Y-m-d',
            'period.worked_time' => 'required|numeric',
            'period.issue_date' => 'required|date_format:Y-m-d',

            // Payment
            'payment' => 'required|array',
            'payment.payment_method_id' => 'required',
            'payment.bank_name' => 'nullable|required_if:payment.payment_method_id,2,3,4,5,6,7,21,22,30,31,42,45,46,47,|string',
            'payment.account_type' => 'nullable|required_if:payment.payment_method_id,2,3,4,5,6,7,21,22,30,31,42,45,46,47,|string',
            'payment.account_number' => 'nullable|required_if:payment.payment_method_id,2,3,4,5,6,7,21,22,30,31,42,45,46,47,|string',


            // Payment Dates
            'payment_dates' => 'required|array',
            'payment_dates.*.payment_date' => 'required|date_format:Y-m-d',

 
            // Accrued
            'accrued' => 'required|array',
            'accrued.worked_days' => 'required|numeric|digits_between:1,2',
            'accrued.salary' => 'required|numeric',
            'accrued.accrued_total' => 'required|numeric',
            
            // heds
            'accrued.heds' => 'nullable|array',
            'accrued.heds.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.heds.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.heds.*.quantity' => 'required|numeric',
            'accrued.heds.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.heds.*.payment' => 'required|numeric',

            // hens
            'accrued.hens' => 'nullable|array',
            'accrued.hens.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hens.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hens.*.quantity' => 'required|numeric',
            'accrued.hens.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.hens.*.payment' => 'required|numeric',
            
            // hrns
            'accrued.hrns' => 'nullable|array',
            'accrued.hrns.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hrns.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hrns.*.quantity' => 'required|numeric',
            'accrued.hrns.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.hrns.*.payment' => 'required|numeric',
            
            // heddfs
            'accrued.heddfs' => 'nullable|array',
            'accrued.heddfs.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.heddfs.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.heddfs.*.quantity' => 'required|numeric',
            'accrued.heddfs.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.heddfs.*.payment' => 'required|numeric',
            
            // hrddfs
            'accrued.hrddfs' => 'nullable|array',
            'accrued.hrddfs.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hrddfs.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hrddfs.*.quantity' => 'required|numeric',
            'accrued.hrddfs.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.hrddfs.*.payment' => 'required|numeric',

            // hendfs
            'accrued.hendfs' => 'nullable|array',
            'accrued.hendfs.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hendfs.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hendfs.*.quantity' => 'required|numeric',
            'accrued.hendfs.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.hendfs.*.payment' => 'required|numeric',

            // hrndfs
            'accrued.hrndfs' => 'nullable|array',
            'accrued.hrndfs.*.start_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hrndfs.*.end_time' => 'required|date_format:Y-m-d\TH:i:s',
            'accrued.hrndfs.*.quantity' => 'required|numeric',
            'accrued.hrndfs.*.percentage' => 'required|exists:tenant.co_type_overtime_surcharges,id',
            'accrued.hrndfs.*.payment' => 'required|numeric',

            // otros conceptos
            'accrued.other_concepts' => 'nullable|array',
            'accrued.other_concepts.*.salary_concept' => 'required_if:accrued.other_concepts.*.non_salary_concept, ""|numeric',
            'accrued.other_concepts.*.non_salary_concept' => 'required_if:accrued.other_concepts.*.salary_concept, ""|numeric',
            'accrued.other_concepts.*.description_concept' => 'required|string',
            
            // prima de servicio
            'accrued.service_bonus' => 'nullable|array',
            'accrued.service_bonus.*.quantity' => 'required|numeric',
            'accrued.service_bonus.*.payment' => 'required|numeric',
            'accrued.service_bonus.*.paymentNS' => 'nullable|numeric',

            // cesantias
            'accrued.severance' => 'nullable|array',
            'accrued.severance.*.payment' => 'required|numeric',
            'accrued.severance.*.percentage' => 'required|numeric',
            'accrued.severance.*.interest_payment' => 'required|numeric',

            // bonificaciones
            'accrued.bonuses' => 'nullable|array',
            'accrued.bonuses.*.salary_bonus' => 'required_if:accrued.bonuses.*.non_salary_bonus, ""|numeric',
            'accrued.bonuses.*.non_salary_bonus' => 'required_if:accrued.bonuses.*.salary_bonus, ""|numeric',

            // ayudas
            'accrued.aid' => 'nullable|array',
            'accrued.aid.*.salary_assistance' => 'required_if:accrued.aid.*.non_salary_assistance, ""|numeric',
            'accrued.aid.*.non_salary_assistance' => 'required_if:accrued.aid.*.salary_assistance, ""|numeric',

            // vacaciones disfrutadas
            'accrued.common_vacation' => 'nullable|array',
            'accrued.common_vacation.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.common_vacation.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.common_vacation.*.quantity' => 'required|numeric',
            'accrued.common_vacation.*.payment' => 'required|numeric',

            // vacaciones compensadas
            'accrued.paid_vacation' => 'nullable|array',
            'accrued.paid_vacation.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.paid_vacation.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.paid_vacation.*.quantity' => 'required|numeric',
            'accrued.paid_vacation.*.payment' => 'required|numeric',

            // discapacidades
            'accrued.work_disabilities' => 'nullable|array',
            'accrued.work_disabilities.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.work_disabilities.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.work_disabilities.*.quantity' => 'required|numeric',
            'accrued.work_disabilities.*.type' => 'required',
            'accrued.work_disabilities.*.payment' => 'required|numeric',

            // licencia maternidad
            'accrued.maternity_leave' => 'nullable|array',
            'accrued.maternity_leave.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.maternity_leave.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.maternity_leave.*.quantity' => 'required|numeric',
            'accrued.maternity_leave.*.payment' => 'required|numeric',

            // licencia remunerada
            'accrued.paid_leave' => 'nullable|array',
            'accrued.paid_leave.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.paid_leave.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.paid_leave.*.quantity' => 'required|numeric',
            'accrued.paid_leave.*.payment' => 'required|numeric',

            // licencia no remunerada
            'accrued.non_paid_leave' => 'nullable|array',
            'accrued.non_paid_leave.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.non_paid_leave.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.non_paid_leave.*.quantity' => 'required|numeric',

            // comisiones
            'accrued.commissions' => 'nullable|array',
            'accrued.commissions.*.commission' => 'required|numeric|gt:0',

            // Bono EPCTVs
            'accrued.epctv_bonuses' => 'nullable|array',
            'accrued.epctv_bonuses.*.paymentS' => 'nullable|numeric|gt:0',
            'accrued.epctv_bonuses.*.paymentNS' => 'nullable|numeric|gt:0',
            'accrued.epctv_bonuses.*.salary_food_payment' => 'nullable|numeric|gt:0',
            'accrued.epctv_bonuses.*.non_salary_food_payment' => 'nullable|numeric|gt:0',

            // pagos terceros
            'accrued.third_party_payments' => 'nullable|array',
            'accrued.third_party_payments.*.third_party_payment' => 'required|numeric|gt:0',

            // anticipos
            'accrued.advances' => 'nullable|array',
            'accrued.advances.*.advance' => 'required|numeric|gt:0',

            // compensaciones
            'accrued.compensations' => 'nullable|array',
            'accrued.compensations.*.ordinary_compensation' => 'required|numeric|gt:0',
            'accrued.compensations.*.extraordinary_compensation' => 'required|numeric|gt:0',

            // huelgas
            'accrued.legal_strike' => 'nullable|array',
            'accrued.legal_strike.*.start_date' => 'required|date_format:Y-m-d',
            'accrued.legal_strike.*.end_date' => 'required|date_format:Y-m-d',
            'accrued.legal_strike.*.quantity' => 'required|numeric',

            'accrued.endowment' => 'nullable|numeric',
            'accrued.sustenance_support' => 'nullable|numeric',
            'accrued.telecommuting' => 'nullable|numeric',
            'accrued.withdrawal_bonus' => 'nullable|numeric',
            'accrued.compensation' => 'nullable|numeric',

            // opcionales
            'accrued.transportation_allowance' => 'nullable|numeric|gt:0',
            'accrued.telecommuting' => 'nullable|numeric|gt:0',
            'accrued.endowment' => 'nullable|numeric|gt:0',
            'accrued.sustenance_support' => 'nullable|numeric|gt:0',
            'accrued.withdrawal_bonus' => 'nullable|numeric|gt:0',
            'accrued.compensation' => 'nullable|numeric|gt:0',

            'accrued.salary_viatics' => 'nullable|numeric|gt:0',
            'accrued.non_salary_viatics' => 'nullable|numeric|gt:0',
            'accrued.refund' => 'nullable|numeric|gt:0',

            // Accrued


            // Deductions
            'deduction' => 'required|array',
            'deduction.eps_type_law_deductions_id' => 'required',
            'deduction.eps_deduction' => 'required|numeric',
            'deduction.pension_type_law_deductions_id' => 'required',
            'deduction.pension_deduction' => 'required|numeric',
            'deduction.deductions_total' => 'required|numeric',

            // Sindicatos
            'deduction.labor_union' => 'nullable|array',
            'deduction.labor_union.*.percentage' => 'required|numeric',
            'deduction.labor_union.*.deduction' => 'required|numeric',

            // Sanciones
            'deduction.sanctions' => 'nullable|array',
            // no puede ir nulo porque genera error en el xml
            'deduction.sanctions.*.public_sanction' => 'required|numeric',
            'deduction.sanctions.*.private_sanction' => 'required|numeric',

            // libranzas
            'deduction.orders' => 'nullable|array',
            'deduction.orders.*.description' => 'required|string',
            'deduction.orders.*.deduction' => 'required|numeric',
            
            // pagos a terceros
            'deduction.third_party_payments' => 'nullable|array',
            'deduction.third_party_payments.*.third_party_payment' => 'required|numeric|gt:0',
            
            // anticipos
            'deduction.advances' => 'nullable|array',
            'deduction.advances.*.advance' => 'required|numeric|gt:0',
            
            // otras deducciones
            'deduction.other_deductions' => 'nullable|array',
            'deduction.other_deductions.*.other_deduction' => 'required|numeric|gt:0',
            
            // opcionales
            'deduction.voluntary_pension' => 'nullable|numeric|gt:0',
            'deduction.withholding_at_source' => 'nullable|numeric|gt:0',
            'deduction.afc' => 'nullable|numeric|gt:0',
            'deduction.cooperative' => 'nullable|numeric|gt:0',
            'deduction.tax_liens' => 'nullable|numeric|gt:0',
            'deduction.supplementary_plan' => 'nullable|numeric|gt:0',
            'deduction.education' => 'nullable|numeric|gt:0',
            'deduction.refund' => 'nullable|numeric|gt:0',
            'deduction.debt' => 'nullable|numeric|gt:0',

            'deduction.fondossp_type_law_deductions_id' => 'nullable|exists:tenant.co_type_law_deductions,id',
            'deduction.fondosp_deduction_SP' => 'required_with:deduction.fondossp_type_law_deductions_id|numeric',
            
            'deduction.fondossp_sub_type_law_deductions_id' => 'nullable|exists:tenant.co_type_law_deductions,id',
            'deduction.fondosp_deduction_sub' => 'required_with:deduction.fondossp_sub_type_law_deductions_id|numeric',

        ];
    }

    
    public function messages()
    {
        return [
            // devengados
            // 'accrued.commissions.*.commission.gt' => 'El campo comisión debe ser mayor que 0.',
            // 'accrued.third_party_payments.*.third_party_payment.gt' => 'El campo pago debe ser mayor que 0.',
            // 'accrued.advances.*.advance.gt' => 'El campo valor anticipo debe ser mayor que 0.',
            // 'accrued.compensations.*.ordinary_compensation.gt' => 'El campo compensación ordinaria anticipo debe ser mayor que 0.',
            // 'accrued.compensations.*.extraordinary_compensation.gt' => 'El campo compensación extraordinaria anticipo debe ser mayor que 0.',

            // deducciones
            // 'deduction.third_party_payments.*.third_party_payment.gt' => 'El campo pago debe ser mayor que 0.',
            // 'deduction.advances.*.advance.gt' => 'El campo valor anticipo debe ser mayor que 0.',
            // 'deduction.other_deductions.*.other_deduction.gt' => 'El campo deducción debe ser mayor que 0.',
        ];
    }

}