<?php

namespace App\Models\System;

use Hyn\Tenancy\Traits\UsesSystemConnection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, UsesSystemConnection;

    protected $fillable = [
        'name', 'email', 'password', 'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Retorna un arreglo con los ids de los modulos permitidos por el sistema
     *
     * @return array
     */
    public function getAllowedModulesForSystem()
    {
        // 1 - Ventas
        // 2 - Compras
        // 4 - Reportes
        // 5 - Configuración
        // 6 - Punto de venta (POS)
        // 7 - Dashboard
        // 8 - Inventario
        // 10 - Ecommerce
        // 12 - Finanzas
        // 13 - Nóminas
        // 20 - Factura del sector salud
        // 21 - RADIAN

        return [1,2,4,5,6,7,8,10,12,13,20,21];
    }

}
