<?php

namespace Modules\Payroll\Models;

use Modules\Factcolombia1\Models\TenantService\{
    TypeLawDeductions
};


class DocumentPayrollDeduction extends PayrollBaseModel
{

    protected $table = 'co_document_payroll_deductions';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 

        'co_document_payroll_id',
        'eps_type_law_deductions_id',
        'eps_deduction',
        'pension_type_law_deductions_id',
        'pension_deduction',
        'labor_union',
        'sanctions',
        'orders',
        'third_party_payments',
        'advances',
        'other_deductions',
        'voluntary_pension',
        'withholding_at_source',
        'afc',
        'cooperative',
        'tax_liens',
        'supplementary_plan',
        'education',
        'refund',
        'debt',
        'deductions_total',

        'fondossp_type_law_deductions_id',
        'fondosp_deduction_SP',
        'fondossp_sub_type_law_deductions_id',
        'fondosp_deduction_sub',
    ];
        

    public function getLaborUnionAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setLaborUnionAttribute($value)
    {
        $this->attributes['labor_union'] = $this->getArrayValueAndValidate($value);
    }

    public function getSanctionsAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setSanctionsAttribute($value)
    {
        $this->attributes['sanctions'] = $this->getArrayValueAndValidate($value);
    }

    public function getOrdersAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setOrdersAttribute($value)
    {
        $this->attributes['orders'] = $this->getArrayValueAndValidate($value);
    }

    public function getThirdPartyPaymentsAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setThirdPartyPaymentsAttribute($value)
    {
        $this->attributes['third_party_payments'] = $this->getArrayValueAndValidate($value);
    }

    public function getAdvancesAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setAdvancesAttribute($value)
    {
        $this->attributes['advances'] = $this->getArrayValueAndValidate($value);
    }

    public function getOtherDeductionsAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setOtherDeductionsAttribute($value)
    {
        $this->attributes['other_deductions'] = $this->getArrayValueAndValidate($value);
    }


    public function eps_type_law_deduction() 
    {
        return $this->belongsTo(TypeLawDeductions::class, 'eps_type_law_deductions_id');
    }

    public function pension_type_law_deduction() 
    {
        return $this->belongsTo(TypeLawDeductions::class, 'pension_type_law_deductions_id');
    }
    
    public function fondossp_type_law_deduction() 
    {
        return $this->belongsTo(TypeLawDeductions::class, 'fondossp_type_law_deductions_id');
    }
    
    public function fondossp_sub_type_law_deduction() 
    {
        return $this->belongsTo(TypeLawDeductions::class, 'fondossp_sub_type_law_deductions_id');
    }

    public function payroll() 
    {
        return $this->belongsTo(DocumentPayroll::class, 'co_document_payroll_id');
    }

    /**
     * Use in resource and collection
     *
     * @return array
     */
    public function getRowResource(){

        return [
            'id' => $this->id, 
            'co_document_payroll_id' => $this->co_document_payroll_id,
            'eps_type_law_deductions_id' => $this->eps_type_law_deductions_id,
            'eps_deduction' => $this->eps_deduction,
            'pension_type_law_deductions_id' => $this->pension_type_law_deductions_id,
            'pension_deduction' => $this->pension_deduction,
            'labor_union' => $this->labor_union,
            'sanctions' => $this->sanctions,
            'orders' => $this->orders,
            'third_party_payments' => $this->third_party_payments,
            'advances' => $this->advances,
            'other_deductions' => $this->other_deductions,
            'voluntary_pension' => $this->voluntary_pension,
            'withholding_at_source' => $this->withholding_at_source,
            'afc' => $this->afc,
            'cooperative' => $this->cooperative,
            'tax_liens' => $this->tax_liens,
            'supplementary_plan' => $this->supplementary_plan,
            'education' => $this->education,
            'refund' => $this->refund,
            'debt' => $this->debt,
            'deductions_total' => $this->deductions_total,
            'fondossp_type_law_deductions_id' => $this->fondossp_type_law_deductions_id,
            'fondosp_deduction_SP' => $this->fondosp_deduction_SP,
            'fondossp_sub_type_law_deductions_id' => $this->fondossp_sub_type_law_deductions_id,
            'fondosp_deduction_sub' => $this->fondosp_deduction_sub,

        ];

    }

    
    /**
     * 
     * Retorna data de nómina afectada, usado cuando se genera nómina de reemplazo
     *
     * @return array
     */
    public function getRowResourceAdjustNote()
    {
        return [

            'co_document_payroll_id' => $this->co_document_payroll_id,
            'eps_type_law_deductions_id' => $this->eps_type_law_deductions_id,
            'eps_deduction' => $this->eps_deduction,
            'pension_type_law_deductions_id' => $this->pension_type_law_deductions_id,
            'pension_deduction' => $this->pension_deduction,

            'labor_union' => $this->checkValueFromArray($this->labor_union),
            'sanctions' => $this->checkValueFromArray($this->sanctions),
            'orders' => $this->checkValueFromArray($this->orders),
            'third_party_payments' => $this->checkValueFromArray($this->third_party_payments),
            'advances' => $this->checkValueFromArray($this->advances),
            'other_deductions' => $this->checkValueFromArray($this->other_deductions),

            'voluntary_pension' => $this->voluntary_pension,
            'withholding_at_source' => $this->withholding_at_source,
            'afc' => $this->afc,
            'cooperative' => $this->cooperative,
            'tax_liens' => $this->tax_liens,
            'supplementary_plan' => $this->supplementary_plan,
            'education' => $this->education,
            'refund' => $this->refund,
            'debt' => $this->debt,
            'deductions_total' => $this->deductions_total,

            'fondossp_type_law_deductions_id' => $this->fondossp_type_law_deductions_id,
            'fondosp_deduction_SP' => $this->fondosp_deduction_SP,
            'fondossp_sub_type_law_deductions_id' => $this->fondossp_sub_type_law_deductions_id,
            'fondosp_deduction_sub' => $this->fondosp_deduction_sub,
        ];

    }

}
