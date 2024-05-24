<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureChangeReferenceIdToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropForeign(['reference_id']);	
            $table->foreign('reference_id')->references('id')->on('documents');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropForeign(['reference_id']);	
            $table->foreign('reference_id')->references('id')->on('co_documents');

        });
    }
}
