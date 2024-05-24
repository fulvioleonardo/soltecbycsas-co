<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Helpers\RegularizeDataHelper;


class TenantCreateCoTypePayrollAdjustNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_type_payroll_adjust_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->char('code');
            $table->timestamps();
        });

        // csv
        RegularizeDataHelper::insertDataFromSeeder('co_type_payroll_adjust_notes');
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_type_payroll_adjust_notes');
    }
}
