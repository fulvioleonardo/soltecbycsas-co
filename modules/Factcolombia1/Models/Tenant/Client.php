<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_clients';
    
          
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type_person_id', 'type_regime_id', 'type_identity_document_id', 'identification_number', 'name', 'country_id', 'department_id', 'city_id', 'address', 'phone', 'email', 'code', 'dv'];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
    * Get the type person belongs to
    */
    public function typePerson() {
        return $this->belongsTo(TypePerson::class);
    }
    
    /**
    * Get the type regime belongs to
    */
    public function typeRegime() {
        return $this->belongsTo(TypeRegime::class);
    }
    
    /**
    * Get the type identity document belongs to
    */
    public function typeIdentityDocument() {
        return $this->belongsTo(TypeIdentityDocument::class);
    }
    
    /**
    * Get the country belongs to
    */
    public function country() {
        return $this->belongsTo(Country::class);
    }
    
    /**
    * Get the department belongs to
    */
    public function department() {
        return $this->belongsTo(Department::class);
    }
    
    /**
    * Get the city belongs to
    */
    public function city() {
        return $this->belongsTo(City::class);
    }
}
