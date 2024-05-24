<?php

namespace Modules\Purchase\Models;

use App\Models\Tenant\{
    ModelTenant,
};
use Modules\Factcolombia1\Models\Tenant\NoteConcept;


class SupportDocumentAdjustNote extends ModelTenant
{

    protected $table = 'co_support_document_adjust_notes';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'co_support_document_id',
        'affected_support_document_id',
        'note_concept_id',
        'discrepancy_response_description',
    ];

    
    /**
     * Relación con documento de ajuste
     *
     */
    public function support_document() 
    {
        return $this->belongsTo(SupportDocument::class, 'co_support_document_id');
    }
    
    /**
     * Relación con documento afectado
     *
     */
    public function affected_support_document() 
    {
        return $this->belongsTo(SupportDocument::class, 'affected_support_document_id');
    }
    
    public function note_concept() 
    {
        return $this->belongsTo(NoteConcept::class);
    }
    
        
    /**
     *
     * @return array
     */
    public function getSupportDocumentAdjustNote() 
    {
        return [
            'number_full' => $this->support_document->number_full,
            // 'note_concept_name' => $this->note_concept->name,
        ];
    }
    
}
