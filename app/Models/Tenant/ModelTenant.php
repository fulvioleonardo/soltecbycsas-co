<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class ModelTenant extends Model
{
    use UsesTenantConnection;
    
    public const EXEMPT_TAX_CODE = '07';
    
    /**
     * 
     * Filtrar registros dependiendo del perfil de usuario
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeGeneralWhereTypeUser($query)
    {
        $user = auth()->user();
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }


    /**
     * 
     * Aplicar formato
     *
     * @param  $value
     * @param  int $decimals
     * @return string
     */
    public function generalApplyNumberFormat($value, $decimals = 2)
    {
        return number_format($value, $decimals, ".", "");
    }
    

    /**
    * 
    * Filtros where like general para buscar campos en listados
    *
    * @param  Builder $query
    * @param  string $column
    * @param  string $value
    * @return Builder
    */
   public function scopeGeneralWhereLikeColumn($query, $column, $value)
   {
       if(empty($value)) return $query;
       
       return $query->where($column, 'like', "%{$value}%");
   }


   /**
    * 
    * Filtros OR where like general para buscar campos en listados
    *
    * @param  Builder $query
    * @param  string $column
    * @param  string $value
    * @return Builder
    */
   public function scopeGeneralOrWhereLikeColumn($query, $column, $value)
   {
       if(empty($value)) return $query;
       
       return $query->orWhere($column, 'like', "%{$value}%");
   }


}