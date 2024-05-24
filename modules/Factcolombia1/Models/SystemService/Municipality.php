<?php

namespace Modules\Factcolombia1\Models\SystemService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Municipality extends Model
{
    use UsesSystemConnection;
    protected $table = 'co_service_municipalities';

    /**
     * With default model.
     *
     * @var array
     */
    protected $with = [
        'department',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id', 'name', 'code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       // 'department_id',
    ];

    /**
     * Get the department identification that owns the department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
