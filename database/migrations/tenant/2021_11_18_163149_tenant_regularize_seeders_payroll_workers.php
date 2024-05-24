<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Helpers\RegularizeDataHelper;

class TenantRegularizeSeedersPayrollWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // co_type_workers
        RegularizeDataHelper::regularizeDataFromTable('co_type_workers');
        
        // co_sub_type_workers
        RegularizeDataHelper::regularizeDataFromTable('co_sub_type_workers');

        // co_payroll_type_document_identifications
        RegularizeDataHelper::regularizeDataFromTable('co_payroll_type_document_identifications');

        // co_type_contracts
        RegularizeDataHelper::regularizeDataFromTable('co_type_contracts');
        
        // co_payroll_periods
        RegularizeDataHelper::regularizeDataFromTable('co_payroll_periods');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
