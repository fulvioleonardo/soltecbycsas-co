<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TenantAddJPGFirmaToCoCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->string('jpg_firma_facturas')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->dropColumn('jpg_firma_facturas');
        });
    }
}
