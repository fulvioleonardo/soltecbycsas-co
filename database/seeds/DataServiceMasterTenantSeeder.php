<?php

use Illuminate\Database\Seeder;

class DataServiceMasterTenantSeeder extends Seeder
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
        'co_service_type_organizations' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_countries' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_departments' => [
            'columns' => 'id, country_id, name, code, @created_at, @updated_at',
        ],
        'co_service_municipalities' => [
            'columns' => 'id, department_id, name, code, @created_at, @updated_at',
        ],
        'co_service_type_document_identifications' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_taxes' => [
            'columns' => 'id, name, description, code, @created_at, @updated_at',
        ],
        'co_service_type_regimes' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_type_liabilities' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],      
        'co_service_type_currencies' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_type_operations' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_type_environments' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_service_languages' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_payment_forms' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
        ],
        'co_payment_methods' => [
            'columns' => 'id, name, code, @created_at, @updated_at',
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
