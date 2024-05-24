<?php

use Illuminate\Database\Seeder;

class UpdateDataServiceMasterTenantSeeder extends Seeder
{
    /**
     * Prefix.
     *
     * @var string
     */
    public $prefix = 'csv';

    /**
     * Tables.
     *
     * @var array
     */
    public $tables = [
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
        'co_type_law_deductions' => [
            'columns' => 'id, name, code, percentage, @created_at, @updated_at'
        ],
        'co_type_disabilities' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_type_overtime_surcharges' => [
            'columns' => 'id, name, code, percentage, type, @created_at, @updated_at'
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach ($this->tables as $key => $table) {
            DB::connection()
                ->getpdo()
                ->exec("LOAD DATA LOCAL INFILE '".str_replace(DIRECTORY_SEPARATOR, '/', public_path($this->prefix.DIRECTORY_SEPARATOR."{$key}.{$this->prefix}"))."' INTO TABLE $key({$table['columns']}) SET created_at = NOW(), updated_at = NOW()");
        }
    }
}
