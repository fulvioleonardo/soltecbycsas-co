<?php

namespace Modules\Payroll\Models;


class DocumentPayrollAdjustNote extends PayrollBaseModel
{

    protected $table = 'co_document_payroll_adjust_notes';
    public $timestamps = false;

    public const ADJUST_NOTE_REPLACE_ID = 1;
    public const ADJUST_NOTE_ELIMINATION_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'co_document_payroll_id',
        'type_payroll_adjust_note_id',
        'affected_document_payroll_id',
    ];

    
    /**
     * Relación con nómina de eliminación o reemplazo
     *
     */
    public function payroll() 
    {
        return $this->belongsTo(DocumentPayroll::class, 'co_document_payroll_id');
    }
    
    /**
     * Relación con nómina afectada
     *
     */
    public function affected_document_payroll() 
    {
        return $this->belongsTo(DocumentPayroll::class, 'affected_document_payroll_id');
    }
    
    public function type_payroll_adjust_note() 
    {
        return $this->belongsTo(TypePayrollAdjustNote::class, 'type_payroll_adjust_note_id');
    }
    
    public function scopeWhereFilterEliminations($query) 
    {
        return $query->where('type_payroll_adjust_note_id', self::ADJUST_NOTE_ELIMINATION_ID);
    }
    
    /**
     * 
     * Validar si es nómina de eliminación
     *
     * @return bool
     */
    public function getIsAdjustNoteEliminationAttribute() 
    {
        return $this->type_payroll_adjust_note_id === self::ADJUST_NOTE_ELIMINATION_ID;
    }

    
    public function getAffectedDocumentPayroll() 
    {
        return [
            'number_full' => $this->payroll->number_full,
            'type_payroll_adjust_note_name' => $this->type_payroll_adjust_note->name,
        ];
    }
    
}
