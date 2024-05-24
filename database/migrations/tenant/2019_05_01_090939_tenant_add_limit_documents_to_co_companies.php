<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TenantAddLimitDocumentsToCoCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->unsignedInteger('limit_documents')->default(0)->after('subdomain');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('co_companies', function(Blueprint $table) {
            $table->dropColumn('limit_documents');
        });
    }
}
