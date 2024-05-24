<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddCoEmailReadingDetailIdToCoReceivedDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_received_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('co_email_reading_detail_id')->nullable()->after('rechazo');
            $table->foreign('co_email_reading_detail_id')->references('id')->on('co_email_reading_details');
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
            $table->dropForeign(['co_email_reading_detail_id']); 
            $table->dropColumn('co_email_reading_detail_id'); 
        });
    }
}
