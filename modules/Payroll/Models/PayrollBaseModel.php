<?php

namespace Modules\Payroll\Models;

use App\Models\Tenant\ModelTenant;

class PayrollBaseModel extends ModelTenant
{

        
    /**
     * Validar y obtener valor del campo, si es nulo retorna arreglo vacío
     * 
     * Usado en:
     * DocumentPayrollAccrued - retornar data para nómina reemplazo
     *
     * @param $value
     * @return array
     */
    public function checkValueFromArray($value)
    {
        return is_null($value) ? [] : $value;
    }
    
    
    /**
     * Validar dato y retornar valor correspondiente para campos tipo json
     * Usado para método get de los atributos
     *
     * @param  mixed $value
     * @return void
     */
    public function getGeneralValueFromAttribute($value)
    {
        return (is_null($value)) ? null : json_decode($value);
    }
    

    /**
     * 
     * Validar dato y retornar valor correspondiente para campos tipo json
     * Usado para método set de los atributos
     *
     * @param $value
     * @return array|null
     */
    public function getArrayValueAndValidate($value)
    {
        return (is_null($value) || empty($value)) ? null : json_encode($value);
    }
    
}
