<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddApiResponseDataToCoServiceCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_service_companies', function (Blueprint $table) {
            $table->text('response_data_api');
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
            $table->dropColumn('response_data_api');
        });
    }
}
