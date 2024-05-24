<?php

namespace Modules\Factcolombia1\Models\SystemService;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    use UsesSystemConnection;

    protected $table = 'co_service_companies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'identification_number', 'dv', 'language_id', 'tax_id', 'type_environment_id', 'type_operation_id', 'type_document_identification_id', 'country_id', 'department_id', 'type_currency_id', 'type_organization_id', 'type_regime_id', 'type_liability_id', 'municipality_id', 'merchant_registration', 'address', 'phone', 'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'software', 'certificate', 'resolutions', 'language', 'tax', 'country', 'type_document_identification', 'type_operation', 'type_environment', 'type_currency', 'type_organization', 'municipality', 'type_liability', 'type_regime',
    ];

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
        return $this->belongsTo(Typey::class)
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

    public function api_token(){
        return $this->attributes['api_token'];
    }
}
