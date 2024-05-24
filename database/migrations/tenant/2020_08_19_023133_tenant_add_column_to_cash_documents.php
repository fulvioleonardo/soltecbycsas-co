<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnToCashDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_documents', function (Blueprint $table) {
            $table->unsignedInteger('document_pos_id')->nullable();
            $table->foreign('document_pos_id')->references('id')->on('documents_pos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_documents', function (Blueprint $table) {
            $table->dropForeign(['document_pos_id']);
            $table->dropColumn('document_pos_id');
        });
    }
}
