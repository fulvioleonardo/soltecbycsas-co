<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddResolutionDateEndToCoTypeDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('co_type_documents', function(Blueprint $table) {
            $table->date('resolution_date_end')->nullable()->after('resolution_date');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('co_type_documents', function(Blueprint $table) {
            $table->dropColumn('resolution_date_end');
        });
    }
}
