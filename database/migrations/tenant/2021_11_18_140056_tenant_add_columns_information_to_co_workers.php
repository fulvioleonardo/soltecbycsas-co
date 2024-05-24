<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsInformationToCoWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_workers', function (Blueprint $table) {

            $table->string('cellphone')->nullable()->after('salary');
            $table->string('email')->nullable()->after('salary');
            $table->string('position')->nullable()->after('salary');
            $table->date('work_start_date')->nullable()->after('salary');
            $table->unsignedBigInteger('payroll_period_id')->nullable()->after('salary');
            $table->foreign('payroll_period_id')->references('id')->on('co_payroll_periods');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('co_workers', function (Blueprint $table) {
            $table->dropColumn('cellphone');
            $table->dropColumn('email');
            $table->dropColumn('position');
            $table->dropColumn('work_start_date');
            $table->dropForeign(['payroll_period_id']);
            $table->dropColumn('payroll_period_id');
        });

    }

}
