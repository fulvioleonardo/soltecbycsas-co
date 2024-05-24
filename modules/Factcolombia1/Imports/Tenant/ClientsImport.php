<?php

namespace Modules\Factcolombia1\Imports\Tenant;

use Maatwebsite\Excel\Concerns\{
    WithChunkReading,
    WithHeadingRow,
    WithValidation,
    ToModel
};
use Modules\Factcolombia1\Models\Tenant\Client;

class ClientsImport implements ToModel, WithValidation, WithHeadingRow, WithChunkReading
{

    public function headingRow(): int {
        return 1;
    }

    public function chunkSize(): int {
        return 500;
    }

    public function rules(): array {
        return [
            'codigo_tipo_de_persona' => 'nullable|exists:tenant.co_type_people,id',
            'codigo_tipo_de_regimen' => 'nullable|exists:tenant.co_type_regimes,id',
            'codigo_tipo_de_documento_de_identidad' => 'nullable|exists:tenant.co_type_identity_documents,id',
            'numero_de_identificacion' => 'nullable|numeric|digits_between:1,15',
            'dv' => 'required',
//            'code' => 'required|unique:tenant.co_clients,code|alpha_dash|max:11',
            'nombre_completo' => 'nullable|max:50',
            'codigo_de_pais' => 'nullable|exists:tenant.co_countries,id',
            'codigo_de_departamento' => 'nullable|exists:tenant.co_departments,id',
            'codigo_de_ciudad' => 'nullable|exists:tenant.co_cities,id',
            'direccion' => 'nullable|max:50',
            'telefono' => 'nullable|integer|digits_between:7,10',
            'correo_electronico' => 'nullable|email|max:50'
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function model(array $row) {
        if ((!empty($row)) && (isset($row['codigo_tipo_de_documento_de_identidad'])) && (isset($row['numero_de_identificacion'])) && (isset($row['nombre_completo']))) {
            return Client::updateOrCreate([
                'type_identity_document_id' => $row['codigo_tipo_de_documento_de_identidad'],
                'identification_number' => $row['numero_de_identificacion'],
            ], [
                'dv' => $row['dv'],
                'code' => $row['codigo_interno'],
                'type_person_id' => $row['codigo_tipo_de_persona'],
                'type_regime_id' => $row['codigo_tipo_de_regimen'],
                'name' => $row['nombre_completo'],
                'country_id' => $row['codigo_de_pais'],
                'department_id' => $row['codigo_de_departamento'],
                'city_id' => $row['codigo_de_ciudad'],
                'address' => $row['direccion'],
                'phone' => $row['telefono'],
                'email' => $row['correo_electronico'],
            ]);
        }

        return null;
    }
}
