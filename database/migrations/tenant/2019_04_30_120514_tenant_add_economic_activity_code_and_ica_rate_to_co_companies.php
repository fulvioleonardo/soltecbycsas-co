<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddEconomicActivityCodeAndIcaRateToCoCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->string('economic_activity_code')->nullable()->after('type_regime_id');
            $table->string('ica_rate')->nullable()->after('economic_activity_code');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->dropColumn(['economic_activity_code', 'ica_rate']);
        });
    }
}
