<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDepartmentIdToCoServiceCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_service_companies', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('country_id');
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
            $table->dropColumn('department_id');
        });
    }
}
