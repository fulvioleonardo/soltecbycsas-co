<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddAcknowledgmentReceivedToCoDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('co_documents', function(Blueprint $table) {
            $table->boolean('acknowledgment_received')->nullable()->after('cufe');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('co_documents', function(Blueprint $table) {
            $table->dropColumn('acknowledgment_received');
        });
    }
}
