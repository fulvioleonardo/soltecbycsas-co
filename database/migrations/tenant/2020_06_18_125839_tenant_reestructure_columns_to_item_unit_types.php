<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToItemUnitTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('item_unit_types', function (Blueprint $table) {
            
            $table->dropForeign(['unit_type_id']);	

        });

        Schema::table('item_unit_types', function (Blueprint $table) {
            
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
        
        Schema::table('item_unit_types', function (Blueprint $table) {

            $table->dropForeign(['unit_type_id']);	

        });

        Schema::table('item_unit_types', function (Blueprint $table) {

            $table->string('unit_type_id')->change();
            $table->foreign('unit_type_id')->references('id')->on('cat_unit_types');

        });
    }
}
