<?php

namespace App\Models\Tenant;

// use App\Models\Tenant\Catalogs\Country;
// use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Catalogs\District;
// use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Models\Tenant\Catalogs\Province;
use Illuminate\Database\Eloquent\Builder;

use Modules\Factcolombia1\Models\Tenant\{
    TypePerson,
    TypeRegime,
    TypeIdentityDocument,
    Country,
    Department,
    City,
};

class Person extends ModelTenant
{

    public const RECORDS_ON_TABLE = 10;

    protected $table = 'persons';
    // protected $with = ['identity_document_type', 'country', 'department', 'province', 'district'];

    protected $fillable = [
        'type',
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'country_id',
        'department_id',
        // 'province_id',
        // 'district_id',
        'address',
        'email',
        'telephone',
        'perception_agent',
        'type_obligation_id',
        // 'state',
        // 'condition',
        'percentage_perception',
        'person_type_id',
        'comment',
        'enabled',
        'type_person_id',
        'type_regime_id',
        'city_id',
        'code',
        'dv',
        'contact_name',
        'contact_phone',
        'postal_code',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('active', function (Builder $builder) {
    //         $builder->where('status', 1);
    //     });
    // }

    public function typeObligation() {
        return $this->belongsTo(TypeObligation::class);
    }

    public function typePerson() {
        return $this->belongsTo(TypePerson::class);
    }

    public function typeRegime() {
        return $this->belongsTo(TypeRegime::class);
    }

    public function identity_document_type()
    {
        return $this->belongsTo(TypeIdentityDocument::class, 'identity_document_type_id');
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }


    public function addresses()
    {
        return $this->hasMany(PersonAddress::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'customer_id');
    }

    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
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

    public function scopeWhereType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getAddressFullAttribute()
    {
        $address = trim($this->address);
        $address = ($address === '-' || $address === '')?'':$address.' ,';
        if ($address === '') {
            return '';
        }
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }

    public function more_address()
    {
        return $this->hasMany(PersonAddress::class);
    }

    public function person_type()
    {
        return $this->belongsTo(PersonType::class);
    }

    public function scopeWhereIsEnabled($query)
    {
        return $query->where('enabled', true);
    }

    
    /**
     * 
     * Retorna arreglo con los datos para la busqueda de clientes
     *
     * @return array
     */
    public function getRowSearchResource()
    {
        return [
            'id' => $this->id,
            'description' => $this->number.' - '.$this->name,
            'name' => $this->name,
            'number' => $this->number,
            'identity_document_type_id' => $this->identity_document_type_id,
            'address' =>  $this->address,
            'email' =>  $this->email,
            'telephone' =>  $this->telephone,
            'type_person_id' => $this->type_person_id,
            'type_regime_id' => $this->type_regime_id,
            'city_id' => $this->city_id,
            'type_obligation_id' => $this->type_obligation_id,
            'dv' => $this->dv,
            'postal_code' => $this->postal_code,
        ];
    }

            
    /**
     * 
     * Filtros para busqueda de clientes
     * Usado en:
     * PersonController
     *
     * @param $query
     * @param $input
     */
    public function scopeWhereFilterSearchCustomer($query, $input)
    {
        return $query->where('number','like', "%{$input}%")
                    ->orWhere('name','like', "%{$input}%")
                    ->whereType('customers')
                    ->orderBy('name');
    }


    /**
     * 
     * Filtros para busqueda de proveedores
     * Usado en:
     * PersonController
     *
     * @param $query
     * @param $input
     */
    public function scopeWhereFilterSearchSupplier($query, $input)
    {
        return $query->where('number','like', "%{$input}%")
                    ->orWhere('name','like', "%{$input}%")
                    ->whereType('suppliers')
                    ->orderBy('name');
    }


}
