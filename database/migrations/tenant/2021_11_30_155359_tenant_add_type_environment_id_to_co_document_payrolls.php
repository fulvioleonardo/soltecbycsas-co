<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTypeEnvironmentIdToCoDocumentPayrolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->unsignedBigInteger('payroll_type_environment_id')->after('user_id')->nullable();
            $table->foreign('payroll_type_environment_id')->references('id')->on('co_service_type_environments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->dropForeign(['payroll_type_environment_id']);
            $table->dropColumn('payroll_type_environment_id');
        });
    }
}
