<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureAddUnitTypeToDocumentItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_items', function (Blueprint $table) {

            $table->unsignedInteger('unit_type_id')->after('item');
            $table->foreign('unit_type_id')->references('id')->on('co_type_units');
            
            $table->dropForeign(['type_unit_id']);	
            $table->dropColumn(['type_unit_id']);	

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_items', function (Blueprint $table) {
            
            $table->dropForeign(['unit_type_id']);	
            $table->dropColumn(['unit_type_id']);	

            $table->unsignedInteger('type_unit_id')->after('item');
            $table->foreign('type_unit_id')->references('id')->on('co_type_units');

        });
    }
}
