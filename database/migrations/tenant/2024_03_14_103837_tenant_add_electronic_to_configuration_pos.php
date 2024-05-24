<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddElectronicToConfigurationPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuration_pos', function (Blueprint $table) {
            $table->boolean('electronic')->default(false)->after('to');
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
            $table->dropColumn('electronic');
        });
    }
}
