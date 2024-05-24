<?php

namespace App\Models\Tenant;

// use App\Models\Tenant\Catalogs\Country;
// use App\Models\Tenant\Catalogs\Department;
// use App\Models\Tenant\Catalogs\District;
// use App\Models\Tenant\Catalogs\Province;

use Modules\Factcolombia1\Models\Tenant\{
    Country,
    Department,
    City,
};

class Establishment extends ModelTenant
{

    protected $with = ['country', 'department', 'city'];

    protected $fillable = [
        'description',
        'country_id',
        'department_id',
        'city_id',
        'address',
        'email',
        'telephone',
        'code',
        'trade_address',
        'establishment_logo',
        'web_address',
        'aditional_information',
    ];

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }

    // public function country()
    // {
    //     return $this->belongsTo(Country::class, 'country_id');
    // }

    // public function department()
    // {
    //     return $this->belongsTo(Department::class);
    // }

    // public function province()
    // {
    //     return $this->belongsTo(Province::class);
    // }

    // public function district()
    // {
    //     return $this->belongsTo(District::class);
    // }

    public function getAddressFullAttribute()
    {
        $address = ($this->address != '-')? $this->address.' ,' : '';
        return "{$address} {$this->country->name} - {$this->department->name} - {$this->city->name}";
    }


    public function scopeFilterWithOutRelations($query)
    {
        return $query->withOut(['country', 'department', 'city']);
    }

}
