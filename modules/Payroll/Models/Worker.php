<?php

namespace Modules\Payroll\Models;

use Modules\Factcolombia1\Models\TenantService\{
    TypeWorker,
    SubTypeWorker,
    PayrollTypeDocumentIdentification,
    TypeContract,
    Municipality,
    PayrollPeriod
};


class Worker extends PayrollBaseModel
{

    protected $table = 'co_workers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'type_worker_id',
        'sub_type_worker_id',
        'payroll_type_document_identification_id',
        'municipality_id',
        'type_contract_id',
        'identification_number',
        'surname',
        'second_surname',
        'first_name',
        'address',
        'high_risk_pension',
        'integral_salarary',
        'salary',

        'cellphone',
        'email',
        'position',
        'work_start_date',
        'payroll_period_id',
        'payment',
    ];

        
    protected $casts = [
        'high_risk_pension' => 'boolean',
        'integral_salarary' => 'boolean', 
    ];


    //se agrega servicio domestico
    public const ID_TYPE_WORKERS_SENA = [2, 4, 6]; 

    public const ID_SUB_TYPE_WORKERS_NOT_PENSION = [2]; 



    public function type_worker()
    {
        return $this->belongsTo(TypeWorker::class);
    }
    
    public function sub_type_worker()
    {
        return $this->belongsTo(SubTypeWorker::class);
    }
    
    public function payroll_type_document_identification()
    {
        return $this->belongsTo(PayrollTypeDocumentIdentification::class);
    }
    
    public function type_contract()
    {
        return $this->belongsTo(TypeContract::class);
    }
    
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function payroll_period() 
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function getPaymentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPaymentAttribute($value)
    {
        $this->attributes['payment'] = (is_null($value))?null:json_encode($value);
    }

    public function getSearchFullNameAttribute()
    { 
        return "{$this->identification_number} - {$this->second_surname} {$this->surname} {$this->first_name}";
    }
 
    public function getFullNameAttribute()
    { 
        return "{$this->second_surname} {$this->surname} {$this->first_name}";
    }
    
    /**
     * Retorna data cuando el campo payment es null
     *
     * @return array
     */
    public function getDefaultDataPayment()
    {
        return [
            'payment_method_id' => null,
            'bank_name' => null,
            'account_type' => null,
            'account_number' => null,
        ];
    }
    
    /**
     * Determinar si el tipo de trabajador esta relacionado al SENA, para no considerar subsidio de transporte, descuento salud  y pension.
     *
     * @return bool
     */
    public function getIsTypeWorkerSenaAttribute()
    {
        return in_array($this->type_worker_id, self::ID_TYPE_WORKERS_SENA);
    }

    /**
     * Determinar si se descuenta pensión al trabajador
     *
     * @return bool
     */
    public function getDiscountPensionAttribute()
    {
        return !in_array($this->sub_type_worker_id, self::ID_SUB_TYPE_WORKERS_NOT_PENSION);
    }

    public function getSearchRowResource()
    { 

        return [
            'id' => $this->id,
            'search_fullname' => $this->search_fullname,
            'salary' => $this->salary,
            'work_start_date' => $this->work_start_date,
            'payroll_period_id' => $this->payroll_period_id,
            'payment' => $this->payment,
            'is_type_worker_sena' => $this->is_type_worker_sena,
            'discount_pension' => $this->discount_pension,
        ];
    }
 

    /**
     * Use in resource and collection
     *
     * @return array
     */
    public function getRowResource(){

        return [
            'id' => $this->id,
            'code' => $this->code,
            'type_worker_id' => $this->type_worker_id,
            'sub_type_worker_id' => $this->sub_type_worker_id,
            'payroll_type_document_identification_id' => $this->payroll_type_document_identification_id,
            'municipality_id' => $this->municipality_id,
            'type_contract_id' => $this->type_contract_id,
            'identification_number' => $this->identification_number,
            'surname' => $this->surname,
            'second_surname' => $this->second_surname,
            'first_name' => $this->first_name,
            'address' => $this->address,
            'high_risk_pension' => $this->high_risk_pension,
            'integral_salarary' => $this->integral_salarary,
            'salary' => $this->salary,
            'fullname' => $this->fullname,
            
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'position' => $this->position,
            'work_start_date' => $this->work_start_date,
            'payroll_period_id' => $this->payroll_period_id,
            'payment' => $this->payment ?? $this->getDefaultDataPayment()
        ];

    }
    
    /**
     * Filtro para busqueda de empleados
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return void
     */
    public function scopeWhereFilterSearch($query, $request)
    { 
        return $query->where('identification_number','like', "%{$request->input}%")
                    ->orWhere('surname','like', "%{$request->input}%") 
                    ->orWhere('second_surname','like', "%{$request->input}%") 
                    ->orWhere('first_name','like', "%{$request->input}%") 
                    ->orderBy('surname');
    }


}
