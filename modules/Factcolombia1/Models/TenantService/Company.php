<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use  Modules\Factcolombia1\Models\Tenant\{
    TypeRegime as TypeRegimeDefault
};

use DateTime;


class Company extends Model
{

    use  UsesTenantConnection;
    protected $table = 'co_service_companies';
    /**
     * With default model.
     *
     * @var array
     */
    protected $with = [
           'language', 'tax', 'country', 'type_document_identification', 'type_operation', 'type_environment', 'type_currency', 'type_organization', 'municipality', 'type_liability', 'type_regime'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'password', 'api_token','user_id', 'identification_number', 'dv', 'language_id', 'tax_id', 'type_environment_id', 'type_operation_id', 'type_document_identification_id', 'country_id', 'department_id', 'type_currency_id', 'type_organization_id', 'type_regime_id', 'type_liability_id', 'municipality_id', 'merchant_registration', 'address', 'phone',
        'id_software', 'pin_software', 'url_software', 'response_software', 'certificate_name', 'password_certificate', 'response_certificate', 'response_resolution', 'test_id', 'response_resolution_credit', 'response_resolution_debit',
        'id_software_payroll', 'pin_software_payroll', 'test_set_id_payroll', 'payroll_type_environment_id', 'id_software_eqdocs', 'pin_software_eqdocs', 'test_set_id_eqdocs', 'eqdocs_type_environment_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*protected $hidden = [
        'software', 'certificate', 'resolutions', 'tax', 'country', 'type_document_identification', 'type_operation', 'type_environment', 'type_currency', 'type_organization', 'municipality', 'type_liability', 'type_regime',
    ];*/

    /**
     * Get the software record associated with the company.
     */
   /* public function software()
    {
        return $this->hasOne(Software::class);
    }*/

    /**
     * Get the certificate record associated with the company.
     */
    /*public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }*/

    /**
     * Get the resolutions record associated with the company.
     */
    /*public function resolutions()
    {
        return $this->hasMany(Resolution::class);
    }*/

    /**
     * Get the language that owns the company.
     */
    public function language()
    {
        return $this->belongsTo(Language::class)
            ->withDefault([
                'id' => 79,
                'name' => 'Spanish; Castilian',
                'code' => 'es',
            ]);
    }

    /**
     * Get the tax that owns the company.
     */
    public function tax()
    {
        return $this->belongsTo(Tax::class)
            ->withDefault([
                'id' => 1,
                'name' => 'IVA',
                'description' => 'Impuesto de Valor Agregado',
                'code' => '01',
            ]);
    }

    /**
     * Get the country that owns the company.
     */
    public function country()
    {
        return $this->belongsTo(Country::class)
            ->withDefault([
                'id' => 46,
                'name' => 'Colombia',
                'code' => 'CO',
            ]);
    }

    /**
     * Get the type operation that owns the company.
     */
    public function type_operation()
    {
        return $this->belongsTo(TypeOperation::class);
    }

    /**
     * Get the type document identification that owns the company.
     */
    public function type_document_identification()
    {
        return $this->belongsTo(TypeDocumentIdentification::class)
            ->withDefault([
                'id' => 3,
                'name' => 'Cédula de ciudadanía',
                'code' => '13',
            ]);
    }

    /**
     * Get the type environment identification that owns the company.
     */
    public function type_environment()
    {
        return $this->belongsTo(TypeEnvironment::class);
    }

    /**
     * Get the type currency identification that owns the company.
     */
    public function type_currency()
    {
        return $this->belongsTo(TypeCurrency::class);
    }

    /**
     * Get the type organization identification that owns the company.
     */
    public function type_organization()
    {
        return $this->belongsTo(TypeOrganization::class)
            ->withDefault([
                'id' => 2,
                'name' => 'Persona Natural',
                'code' => '2',
            ]);
    }

    /**
     * Get the municipality identification that owns the company.
     */
    public function municipality()
    {
        return $this->belongsTo(Municipality::class)
            ->withDefault([
                'id' => 1006,
                'department_id' => 31,
                'name' => 'Cali',
                'code' => '76001',
            ]);
    }

    /**
     * Get the type liability identification that owns the company.
     */
    public function type_liability()
    {
        return $this->belongsTo(TypeLiability::class)
            ->withDefault([
                'id' => 117,
                'name' => 'No Responsable',
                'code' => 'R-99-PN',
            ]);
    }

    /**
     * Get the type regime identification that owns the company.
     */
    public function type_regime()
    {
        return $this->belongsTo(TypeRegime::class)
            ->withDefault([
                'id' => 1,
                'name' => 'Régimen Simple',
                'code' => '04',
            ]);
    }

    /**
     * Get the type regime identification that owns the company.
     */
    public function typeregime()
    {
        return $this->belongsTo('App\Models\Tenant\TypeRegime', 'type_regime_id');
       /* return $this->belongsTo(TypeRegimeDefault::class)
            ->withDefault([
                'id' => 1,
                'name' => 'Simplificado',
                'code' => '0',
            ]);*/
    }

    /**
     *
     * No incluir las relaciones iniciales asociadas al modelo
     *
     * @param $query
     */
    public function scopeWhereFilterWithOutAllRelations($query)
    {
        return $query->withOut(['language', 'tax', 'country', 'type_document_identification', 'type_operation', 'type_environment', 'type_currency', 'type_organization', 'municipality', 'type_liability', 'type_regime']);
    }

    /**
     * Get the send that owns the company.
     */
    /*public function send()
    {
        return $this->hasMany(Send::class)
            ->where('year', now()->format('y'));
    }*/

    public function getResolutionModelApiAttribute()
    {
        if($this->response_resolution)
        {
            $model = json_decode($this->response_resolution);
            $inivigencia =  new DateTime($model->resolution->date_from);
            $finvigencia = new DateTime($model->resolution->date_to);
            $difvigencia = $inivigencia->diff($finvigencia);
            $mes_total_vigencia = $difvigencia->m;
            $model->resolution->vigencia = $mes_total_vigencia;
            return $model->resolution;
        }
        else{
            return null;
        }

    }

    protected $appends = ['resolution_model_api'];
}
