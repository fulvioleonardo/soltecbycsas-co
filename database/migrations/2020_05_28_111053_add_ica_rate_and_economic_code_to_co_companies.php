<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIcaRateAndEconomicCodeToCoCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_companies', function (Blueprint $table) {
            $table->string('economic_activity_code')->nullable()->after('hostname_id');
            $table->string('ica_rate')->nullable()->after('hostname_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_companies', function (Blueprint $table) {
            $table->dropColumn(['economic_activity_code', 'ica_rate']);
        });
    }
}
