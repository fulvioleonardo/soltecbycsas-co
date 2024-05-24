<?php

namespace Modules\Factcolombia1\Imports\Tenant;

use Maatwebsite\Excel\Concerns\{
    WithChunkReading,
    WithHeadingRow,
    WithValidation,
    ToModel
};

use Modules\Factcolombia1\Models\Tenant\Item;

class ItemsImport implements ToModel, WithValidation, WithHeadingRow, WithChunkReading
{
    public function headingRow(): int {
        return 1;
    }

    public function chunkSize(): int {
        return 500;
    }

    public function rules(): array {
        return [
            'nombre' => 'nullable|string|max:50',
            'codigo_interno' => 'nullable|alpha_dash|max:11',
            'codigo_tipo_de_unidad' => 'nullable|exists:tenant.co_type_units,id',
            'precio' => 'nullable|numeric|between:0.00,9999999999.99',
            'codigo_del_impuesto' => 'nullable|exists:tenant.co_taxes,id'
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row) {
        if ((!empty($row)) && (isset($row['nombre'])) && (isset($row['codigo_interno'])) && (isset($row['codigo_tipo_de_unidad'])) && (isset($row['precio']))) {
            return Item::updateOrCreate([
                'code' => mb_strtoupper($row['codigo_interno'])
            ], [
                'name' => mb_strtoupper($row['nombre']),
                'type_unit_id' => $row['codigo_tipo_de_unidad'],
                'price' => $row['precio'],
                'tax_id' => $row['codigo_del_impuesto']
            ]);
        }

        return null;
    }
}
