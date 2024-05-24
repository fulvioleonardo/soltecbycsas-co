<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TenantAddEstablishmentLogoToEstablishments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('establishments', function(Blueprint $table) {
            $table->string('establishment_logo')->nullable()->after('trade_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('establishments', function(Blueprint $table) {
            $table->dropColumn('establishment_logo');
        });
    }
}
