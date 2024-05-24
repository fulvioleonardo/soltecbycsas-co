<?php

namespace Modules\Factcolombia1\Models\Tenant;

use Illuminate\Database\Eloquent\SoftDeletes;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $table = 'co_cities';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'department_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Función para actualizar las cuidades que no existen en el pro 1 pero si en la api
     *
     * @param string $createdAt
     * @return void
     */
	public static function updateCities($createdAt = '2020-12-23 10:00:00')
	{
		$data = [
			['name' => 'Ábrego', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Arboledas', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Bochalema', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Bucarasica', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Cáchira', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Cácota', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Chinácota', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Chitagá', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Convención', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Cucutilla', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Durania', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'El Carmen', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'El Tarra', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'El Zulia', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Gramalote', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Hacarí', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Herrán', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Esperanza', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Playa', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Labateca', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Los Patios', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Lourdes', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Mutiscua', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Ocaña', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Pamplona', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Pamplonita', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Puerto Santander', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Ragonvalia', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Salazar', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San Calixto', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San Cayetano', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San José De Cúcuta', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Santiago', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Sardinata', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Silos', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Teorama', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Tibú', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Toledo', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Villa Caro', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Villa Del Rosario', 'department_id' => 797, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Barranco Minas', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Mapiripana', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San Felipe ', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Puerto Colombia ', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Guadalupe ', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Cacahual', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Pana Pana ', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Morichal', 'department_id' => 790, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'El Encanto', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Chorrera', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Pedrera', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Victoria', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Mirití - Paraná', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Puerto Alegría', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Puerto Arica', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Puerto Santander', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Tarapacá', 'department_id' => 775, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Venecia', 'department_id' => 789, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Apulo', 'department_id' => 789, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Albania', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Barrancas', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Dibulla', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Distracción', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'El Molino ', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Fonseca', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Hatonuevo', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'La Jagua Del Pilar', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Maicao', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Manaure', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Riohacha', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San Juan Del Cesar', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Uribia', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Urumita', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Villanueva', 'department_id' => 793, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Armero', 'department_id' => 804, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Carurú', 'department_id' => 806, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Coveñas', 'department_id' => 803, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Guachené', 'department_id' => 785, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Guamal', 'department_id' => 795, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Norosí', 'department_id' => 780, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Pacoa', 'department_id' => 806, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Patía', 'department_id' => 785, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San José De Uré', 'department_id' => 788, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'San Luis De Sincé', 'department_id' => 803, 'created_at' => $createdAt, 'updated_at' => $createdAt],
			['name' => 'Tuchín', 'department_id' => 788, 'created_at' => $createdAt, 'updated_at' => $createdAt],
		];
		City::query()->insert($data);
	}
}
