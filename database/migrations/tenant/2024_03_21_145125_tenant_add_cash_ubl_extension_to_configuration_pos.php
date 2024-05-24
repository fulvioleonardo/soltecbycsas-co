<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddCashUBLExtensionToConfigurationPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuration_pos', function (Blueprint $table) {
            $table->string('plate_number')->nullable()->after('electronic');
            $table->string('cash_type')->nullable()->after('plate_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuration_pos', function (Blueprint $table) {
            $table->dropColumn('plate_number');
            $table->dropColumn('cash_type');
        });
    }
}
