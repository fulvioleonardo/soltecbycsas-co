<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class NoteConcept extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_note_concepts';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type_document_id', 'name', 'code'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
