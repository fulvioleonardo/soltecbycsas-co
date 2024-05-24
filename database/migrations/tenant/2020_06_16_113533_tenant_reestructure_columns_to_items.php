<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('items', function (Blueprint $table) {
            
            $table->unique('name');
            $table->unique('internal_id');		
            $table->dropForeign(['unit_type_id']);	
            $table->unsignedInteger('tax_id')->after('item_code_gs1');
            $table->foreign('tax_id')->references('id')->on('co_taxes');

        });

        Schema::table('items', function (Blueprint $table) {
            
            $table->unsignedInteger('unit_type_id')->change();
            $table->foreign('unit_type_id')->references('id')->on('co_type_units');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('items', function (Blueprint $table) {

            $table->dropUnique(['name']);	
            $table->dropUnique(['internal_id']);
            $table->dropForeign(['unit_type_id']);	
            $table->dropForeign(['tax_id']);	
            $table->dropColumn('tax_id');

        });

        Schema::table('items', function (Blueprint $table) {

            $table->string('unit_type_id')->change();
            $table->foreign('unit_type_id')->references('id')->on('cat_unit_types');

        });

    }
}
