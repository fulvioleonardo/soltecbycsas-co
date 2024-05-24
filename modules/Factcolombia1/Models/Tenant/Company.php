<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_companies';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type_environment_id', 'type_identity_document_id', 'identification_number', 'name', 'short_name', 'email', 'logo', 'subdomain', 'limit_documents', 'country_id', 'department_id', 'city_id', 'address', 'phone', 'currency_id', 'type_regime_id', 'economic_activity_code', 'ica_rate', 'type_obligation_id', 'version_ubl_id', 'ambient_id', 'software_identifier', 'software_password', 'pin', 'certificate_name', 'certificate_password', 'certificate_date_end'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public static function active()
    {
        return Company::first();
    }

    /**
    * Get the type identity document belongs to
    */
    public function type_identity_document() {
        return $this->belongsTo(TypeIdentityDocument::class);
    }

    /**
    * Get the country belongs to
    */
    public function country() {
        return $this->belongsTo(Country::class);
    }

    /**
    * Get the currency belongs to
    */
    public function currency() {
        return $this->belongsTo(Currency::class);
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

    /**
    * Get the version ubl belongs to
    */
    public function version_ubl() {
        return $this->belongsTo(VersionUbl::class);
    }

    /**
    * Get the ambient belongs to
    */
    public function ambient() {
        return $this->belongsTo(Ambient::class);
    }

    /**
    * Get the type regime belongs to
    */
    public function type_regime() {
        return $this->belongsTo(TypeRegime::class);
    }
}
