<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsPayrollToCoServiceCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_service_companies', function (Blueprint $table) {
            $table->string('test_set_id_payroll')->nullable();
            $table->string('pin_software_payroll')->nullable();
            $table->string('id_software_payroll')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_service_companies', function (Blueprint $table) {
            $table->dropColumn('id_software_payroll');
            $table->dropColumn('pin_software_payroll');
            $table->dropColumn('test_set_id_payroll');
        });
    }
}
