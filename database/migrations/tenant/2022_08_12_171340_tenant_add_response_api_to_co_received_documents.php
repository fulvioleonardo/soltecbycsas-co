<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddResponseApiToCoReceivedDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_received_documents', function (Blueprint $table) {
            $table->json('response_api')->nullable()->after('rechazo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_received_documents', function (Blueprint $table) {
            $table->dropColumn('response_api'); 
        });
    }
}
