<?php

namespace Modules\Factcolombia1\Helpers;

use DB;

class RegularizeDataHelper
{

    /**
     * Regularizar data de las tablas con errores
     * Verifica si tiene registros (seeders con errores)
     * Elimina la data
     * Regulariza con la data actualizada del .csv
     *
     * @param  string $table_name
     * @return void
     */
    public static function regularizeDataFromTable($table_name)
    {

        $exist_records = self::countRecords($table_name);

        // si hay registros se eliminan para ejecutar los seeders corregidos
        if($exist_records > 0)
        {
            self::deleteRecords($table_name);
            self::insertDataFromSeeder($table_name);
        }

    }

    public static function insertDataFromSeeder($table_name)
    {

        $tables = [
            'co_type_workers' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_sub_type_workers' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_payroll_type_document_identifications' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_type_contracts' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_payroll_periods' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_type_overtime_surcharges' => [
                'columns' => 'id, name, code, percentage, type, @created_at, @updated_at'
            ],
            'co_type_payroll_adjust_notes' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_type_generation_transmitions' => [
                'columns' => 'id, name, code, @created_at, @updated_at'
            ],
            'co_service_type_documents' => [
                'columns' => 'id, name, code, cufe_algorithm, prefix, @created_at, @updated_at'
            ],
            'co_health_type_document_identifications' => [
               'columns' => 'id, name, code, @created_at, @updated_at'
            ],
        ];

        $prefix = 'csv';
        $key = $table_name;
        $table = $tables[$table_name];

        DB::connection()
            ->getpdo()
            ->exec("LOAD DATA LOCAL INFILE '".str_replace(DIRECTORY_SEPARATOR, '/', public_path($prefix.DIRECTORY_SEPARATOR."{$key}.{$prefix}"))."' INTO TABLE $key({$table['columns']}) SET created_at = NOW(), updated_at = NOW()");
    }


    public static function deleteRecords($table)
    {
        DB::table($table)->delete();
    }


    public static function countRecords($table)
    {
        return DB::table($table)->count();
    }

}
