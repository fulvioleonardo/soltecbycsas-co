<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddStateDocumentIdToCoDocumentPayrolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->unsignedInteger('state_document_id')->after('user_id')->nullable();
            $table->foreign('state_document_id')->references('id')->on('co_state_documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->dropForeign(['state_document_id']);
            $table->dropColumn('state_document_id');
        });
    }
}
