<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddCertificateDateEndToCoCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->date('certificate_date_end')->nullable()->after('certificate_password');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->dropColumn('certificate_date_end');
        });
    }
}
