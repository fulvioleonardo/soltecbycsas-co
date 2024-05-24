<?php

namespace Modules\Factcolombia1\Models\TenantService;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class AdvancedConfiguration extends Model
{

    use  UsesTenantConnection;

    protected $table = 'co_advanced_configuration';

    public const QUANTITY_UVT_LIMIT = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'minimum_salary',
        'transportation_allowance',

        'radian_imap_encryption',
        'radian_imap_host',
        'radian_imap_port',
        'radian_imap_password',
        'radian_imap_user',
        'uvt',

    ];

      
    protected $casts = [
        'uvt' => 'float',
    ];
  
    
    /**
     * Use in resource and collection
     *
     * @return array
     */
    public function getRowResource(){

        return [
            'id' => $this->id,
            'minimum_salary' => $this->minimum_salary,
            'transportation_allowance' => $this->transportation_allowance,
            
            'radian_imap_encryption' => $this->radian_imap_encryption,
            'radian_imap_host' => $this->radian_imap_host,
            'radian_imap_port' => $this->radian_imap_port,
            'radian_imap_password' => $this->radian_imap_password,
            'radian_imap_user' => $this->radian_imap_user,
            'uvt' => $this->uvt,
        ];

    }
    
    
    public function scopeSelectImapColumns($query)
    {
        return $query->select([
            'radian_imap_encryption',
            'radian_imap_host',
            'radian_imap_port',
            'radian_imap_password',
            'radian_imap_user',
        ]);
    }

    
    /**
     * 
     * Configuracion para forms
     *
     * @param  array $columns
     * @return AdvancedConfiguration
     */
    public static function getPublicConfiguration($columns = [])
    {
        $query = self::query();

        if(!empty($columns)) $query->select($columns);

        return $query->firstOrFail();
    }

    
    /**
     * 
     * Limite de la uvt para validar registro de documento en pos
     *
     * @return float
     */
    public function getLimitUvt()
    {
        return $this->uvt * self::QUANTITY_UVT_LIMIT;
    }

}
