<?php

namespace App\Models\Tenant;

class ConfigurationPos extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'prefix',
        'resolution_number',
        'resolution_date',
        'date_from',
        'date_end',
        'from',
        'to',
        'electronic',
        'plate_number',
        'cash_type',
    ];

    protected $casts = [
        'resolution_date' => 'date',
        'electronic' => 'boolean',
    ];
}
