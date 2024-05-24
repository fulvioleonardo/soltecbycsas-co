<?php

namespace Modules\Factcolombia1\Jobs\Tenant;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Hyn\Tenancy\Queue\TenantAwareJob;
use Illuminate\Bus\Queueable;
use App\Models\Tenant\Tax;
use DB;

class ConfigureTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, TenantAwareJob;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;
    public $prefix = 'csv';

    /**
     * Tables
     * @var array
     */
    public $tables = [
        'countries' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'countries.csv',
            'columns' => 'id, code, name, code_phone, @created_at, @updated_at'
        ],
        'departments' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'departments.csv',
            'columns' => 'id, name, country_id, @created_at, @updated_at'
        ],
        'cities' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'cities.csv',
            'columns' => 'id, name, department_id, @created_at, @updated_at'
        ],
        'currencies' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'currencies.csv',
            'columns' => 'id, code, name, symbol, @created_at, @updated_at'
        ],
        'type_identity_documents' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_identity_documents.csv',
            'columns' => 'id, code, name, @created_at, @updated_at'
        ],
        'type_regimes' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_regimes.csv',
            'columns' => 'id, code, name, @created_at, @updated_at'
        ],
        'type_obligations' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_obligations.csv',
            'columns' => 'id, code, name, @created_at, @updated_at'
        ],
        'taxes' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'taxes.csv',
            'columns' => 'id, name, code, rate, conversion, is_percentage, is_fixed_value, is_retention, in_base, @created_at, @updated_at'
        ],
        'version_ubls' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'version_ubls.csv',
            'columns' => 'id, name, @created_at, @updated_at'
        ],
        'type_people' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_people.csv',
            'columns' => 'id, name, @created_at, @updated_at'
        ],
        'type_units' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_units.csv',
            'columns' => 'id, name, @created_at, @updated_at'
        ],
        'ambients' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'ambients.csv',
            'columns' => 'id, name, url, @created_at, @updated_at'
        ],
        'type_invoices' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_invoices.csv',
            'columns' => 'id, name, code, @created_at, @updated_at'
        ],
        'type_documents' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'type_documents.csv',
            'columns' => 'id, name, code, template, @created_at, @updated_at'
        ],
        'state_documents' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'state_documents.csv',
            'columns' => 'id, name, @created_at, @updated_at'
        ],
        'state_quotes' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'state_quotes.csv',
            'columns' => 'id, name, @created_at, @updated_at'
        ],
        'note_concepts' => [
            'file' => 'csv'.DIRECTORY_SEPARATOR.'note_concepts.csv',
            'columns' => 'id, type_document_id, name, code, @created_at, @updated_at'
        ]
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        foreach ($this->tables as $key => $table) {
            DB::connection('tenant')
                ->getpdo()
               ->exec("LOAD DATA LOCAL INFILE '".str_replace(DIRECTORY_SEPARATOR, '/', public_path($this->prefix.DIRECTORY_SEPARATOR."{$key}.{$this->prefix}"))."' INTO TABLE $key({$table['columns']}) SET created_at = NOW(), updated_at = NOW()");
        }

        $tax = Tax::find(6);
        $tax->in_tax = 1;
        $tax->save();
    }
}
