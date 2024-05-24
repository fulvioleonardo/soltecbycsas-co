<?php

namespace Modules\Payroll\Models;

use App\Models\Tenant\{
    User
};
use Modules\Factcolombia1\Models\Tenant\{
    TypeDocument,
    StateDocument,
};
use Modules\Factcolombia1\Models\TenantService\{
    PayrollPeriod,
    TypeEnvironment
};
use App\Models\Tenant\{
    Establishment
};


class DocumentPayroll extends PayrollBaseModel
{

    protected $table = 'co_document_payrolls';

    public const ADJUST_NOTE_TYPE_DOCUMENT_ID = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 

        'external_id',
        'user_id',
        'date_of_issue',
        'time_of_issue',

        'type_document_id',
        'establishment_id',
        'establishment',

        'head_note',
        'foot_note',

        'novelty',

        'period',
        
        'prefix',
        'consecutive',

        'payroll_period_id',
        'notes',
        
        'worker_id',
        'worker',
        
        'payment',
        'payment_dates',
        'response_api',
        'payroll_type_environment_id',
        'state_document_id',
        'response_message_query_zipkey',

    ];
        
    protected $casts = [
        'date_of_issue' => 'date',
    ];


    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getNoveltyAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setNoveltyAttribute($value)
    {
        $this->attributes['novelty'] = (is_null($value))?null:json_encode($value);
    }

    public function getPeriodAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPeriodAttribute($value)
    {
        $this->attributes['period'] = (is_null($value))?null:json_encode($value);
    }

    public function getWorkerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setWorkerAttribute($value)
    {
        $this->attributes['worker'] = (is_null($value))?null:json_encode($value);
    }

    public function getPaymentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPaymentAttribute($value)
    {
        $this->attributes['payment'] = (is_null($value))?null:json_encode($value);
    }

    public function getPaymentDatesAttribute($value)
    {
        return $this->getGeneralValueFromAttribute($value);
    }

    public function setPaymentDatesAttribute($value)
    {
        $this->attributes['payment_dates'] = (is_null($value))?null:json_encode($value);
    }
    
    public function getResponseApiAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setResponseApiAttribute($value)
    {
        $this->attributes['response_api'] = (is_null($value))?null:json_encode($value);
    }

    public function state_document() 
    {
        return $this->belongsTo(StateDocument::class);
    }

    public function type_environment()
    {
        return $this->belongsTo(TypeEnvironment::class, 'payroll_type_environment_id');
    }

    public function type_document() 
    {
        return $this->belongsTo(TypeDocument::class);
    }

    public function establishment() 
    {
        return $this->belongsTo(Establishment::class);
    }

    public function payroll_period() 
    {
        return $this->belongsTo(PayrollPeriod::class);
    }
    
    public function affected_adjust_notes()
    {
        return $this->hasMany(DocumentPayrollAdjustNote::class, 'affected_document_payroll_id');
    }

    public function adjust_note() 
    {
        return $this->hasOne(DocumentPayrollAdjustNote::class, 'co_document_payroll_id');
    }
    
    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function model_worker() 
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

    public function accrued() 
    {
        return $this->hasOne(DocumentPayrollAccrued::class, 'co_document_payroll_id');
    }

    public function deduction() 
    {
        return $this->hasOne(DocumentPayrollDeduction::class, 'co_document_payroll_id');
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->consecutive;
    }
        

    /**
     * 
     * Filtros para listado de nóminas
     *
     * @param $query
     * @param $request
     */
    public function scopeWhereFilterRecords($query, $request)
    {

        if(!is_null($request->value) && $request->value != '')
        {
            if($request->column === 'consecutive')
            {
                return $query->where('consecutive', $request->value);
            }
    
            return $query->where($request->column, 'like', "%{$request->value}%");
        }

        return $query;
    }


    /**
     * Determina si es nómina de ajuste
     *
     * @return bool
     */
    public function getIsPayrollAdjustNoteAttribute()
    {
        return !is_null($this->adjust_note);
    }
    

    public function getTypeDocumentName()
    {
        return $this->type_document->name;
    }


    /**
     * Descripción para diferenciar el tipo de nómina
     *
     * @return string
     */
    public function getTypePayrollDescriptionAttribute()
    {
        $description = $this->getTypeDocumentName();

        if($this->is_payroll_adjust_note)  $description .= " ({$this->adjust_note->type_payroll_adjust_note->name})";

        return $description;
    }


    /**
     * Use in collection
     *
     * @return array
     */
    public function getRowCollection(){

        $filename_xml = null;
        $filename_pdf = null;
        
        if($this->response_api)
        {
            $response = $this->response_api;
            $response_api_message = isset($response->message) ? $response->message:null;

            $filename_xml = $response->urlpayrollxml ?? null;
            $filename_pdf =  $response->urlpayrollpdf ?? null;
        }

        //mostrar el boton consultar si el estado es registrado y el entorno es habilitacion
        $btn_query = false;

        if($this->state_document_id === 1 && $this->payroll_type_environment_id == 2){
            $btn_query = true;
        }

        // nomina eliminacion y reemplazo
        $btn_adjust_note_elimination = false;
        $btn_adjust_note_replace = false;
        
        // si es aceptada y no es nomina de ajuste 
        if($this->state_document_id === 5 && !$this->is_payroll_adjust_note)
        {
            //solo puede tener 1 nómina de eliminación
            $btn_adjust_note_elimination = ($this->affected_adjust_notes()->whereFilterEliminations()->count() === 0);
            $btn_adjust_note_replace = true;
        }

        $affected_adjust_notes = $this->getDocumentPayrollRelated();
        // nomina eliminacion y reemplazo


        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'prefix' => $this->prefix,
            'consecutive' => $this->consecutive,
            'number_full' => $this->number_full,
            'worker_id' => $this->worker_id,
            'worker_full_name' => $this->model_worker->full_name,
            'worker_email' => $this->model_worker->email,

            'salary' => optional($this->accrued)->salary,
            'accrued_total' => optional($this->accrued)->accrued_total,
            'deductions_total' => optional($this->deduction)->deductions_total,

            'filename_xml' => $filename_xml,
            'filename_pdf' => $filename_pdf,

            'state_document_id' => $this->state_document_id,
            'state_document_name' => optional($this->state_document)->name,
            'btn_query' => $btn_query,
            'response_message_query_zipkey' => $this->response_message_query_zipkey,
            'payroll_type_environment_id' => $this->payroll_type_environment_id,
            'type_payroll_description' => $this->type_payroll_description,
            'btn_adjust_note_elimination' => $btn_adjust_note_elimination,
            'btn_adjust_note_replace' => $btn_adjust_note_replace,
            'affected_adjust_notes' => $affected_adjust_notes,
            
        ];

    }
    
    /**
     * 
     * Obtener nóminas relacionados (individuales o de ajuste)
     *
     * @return array
     */
    public function getDocumentPayrollRelated()
    {

        $affected_adjust_notes = $this->affected_adjust_notes->transform(function($row){
            return $row->getAffectedDocumentPayroll();
        });

        if($this->is_payroll_adjust_note){

            $affected_adjust_notes->push([
                'number_full' => $this->adjust_note->affected_document_payroll->number_full,
                'type_payroll_adjust_note_name' => null,
            ]);
        }

        return $affected_adjust_notes;
    }


    /**
     * Use in resource
     *
     * @return array
     */
    public function getRowResource()
    {

        $filename_xml = null;
        $filename_pdf = null;
        
        if($this->response_api)
        {
            $response = $this->response_api;
            $response_api_message = isset($response->message) ? $response->message:null;

            $filename_xml = $response->urlpayrollxml ?? null;
            $filename_pdf =  $response->urlpayrollpdf ?? null;
        }


        return [
            'id' => $this->id,
            'external_id' => $this->external_id,
            'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
            'time_of_issue' => $this->time_of_issue,
            'type_document_id' => $this->type_document_id,
            'establishment_id' => $this->establishment_id,
            'establishment' => $this->establishment,
            'head_note' => $this->head_note,
            'foot_note' => $this->foot_note,
            'novelty' => $this->novelty,
            'period' => $this->period,
            'prefix' => $this->prefix,
            'consecutive' => $this->consecutive,
            'number_full' => $this->number_full,
            'payroll_period_id' => $this->payroll_period_id,
            'notes' => $this->notes,
            'worker_id' => $this->worker_id,
            'worker' => $this->worker,
            'worker_full_name' => $this->model_worker->full_name,
            'worker_email' => $this->model_worker->email,
            'payment' => $this->payment,
            'payment_dates' => $this->payment_dates,
            'response_api_message' => $response_api_message,

            'salary' => optional($this->accrued)->salary,
            'accrued_total' => optional($this->accrued)->accrued_total,
            'deductions_total' => optional($this->deduction)->deductions_total,

            'filename_xml' => $filename_xml,
            'filename_pdf' => $filename_pdf,

            'state_document_id' => $this->state_document_id,
            'state_document_name' => optional($this->state_document)->name,
            'response_message_query_zipkey' => $this->response_message_query_zipkey,
            'payroll_type_environment_id' => $this->payroll_type_environment_id,
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

            'date_of_issue' => $this->date_of_issue,
            'time_of_issue' => $this->time_of_issue,
    
            'number_full' => $this->number_full,
    
            'head_note' => $this->head_note,
            'foot_note' => $this->foot_note,
    
            'novelty' => $this->novelty,
    
            'period' => $this->period,
            
            'payroll_period_id' => $this->payroll_period_id,
            'notes' => $this->notes,
            
            'worker_id' => $this->worker_id,
            'worker_total_base_salary' => $this->worker->salary,
            'payment' => $this->payment,
            'payment_dates' => $this->payment_dates,
            'accrued' => $this->accrued->getRowResourceAdjustNote(),
            'deduction' => $this->deduction->getRowResourceAdjustNote(),

        ];
    }
    

}
