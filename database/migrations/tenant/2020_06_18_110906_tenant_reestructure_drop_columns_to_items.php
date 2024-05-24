<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureDropColumnsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            
            $table->dropColumn(['item_code']);
            $table->dropColumn(['item_code_gs1']);
            
            $table->dropForeign(['currency_type_id']);	

            $table->dropColumn(['has_igv', 'has_isc']);

            $table->dropForeign(['system_isc_type_id']);	
            $table->dropColumn(['system_isc_type_id']);

            $table->dropColumn(['percentage_isc']);
            $table->dropColumn(['suggested_price']);
            
            $table->dropForeign(['sale_affectation_igv_type_id']);	
            $table->dropColumn(['sale_affectation_igv_type_id']);
            
            $table->dropForeign(['purchase_affectation_igv_type_id']);	
            $table->dropColumn(['purchase_affectation_igv_type_id']);

            $table->unsignedInteger('purchase_tax_id')->nullable()->after('tax_id');
            $table->foreign('purchase_tax_id')->references('id')->on('co_taxes');


        });

        Schema::table('items', function (Blueprint $table) {

            $table->unsignedInteger('currency_type_id')->change();
            $table->foreign('currency_type_id')->references('id')->on('co_currencies');

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
            
            $table->string('item_code')->nullable();
            $table->string('item_code_gs1')->nullable();

            $table->dropForeign(['currency_type_id']);	

            $table->boolean('has_igv')->default(true)->after('sale_unit_price');
            $table->boolean('has_isc')->default(false);

            $table->string('system_isc_type_id')->nullable();
            $table->foreign('system_isc_type_id')->references('id')->on('cat_system_isc_types');

            $table->decimal('percentage_isc', 12, 2)->default(0);
            $table->decimal('suggested_price', 12, 2)->default(0);
            
            $table->string('sale_affectation_igv_type_id')->nullable();
            $table->string('purchase_affectation_igv_type_id')->nullable();
            
            $table->foreign('sale_affectation_igv_type_id')->references('id')->on('cat_affectation_igv_types');
            $table->foreign('purchase_affectation_igv_type_id')->references('id')->on('cat_affectation_igv_types');
            
            $table->dropForeign(['purchase_tax_id']);	
            $table->dropColumn(['purchase_tax_id']);

        });

        
        Schema::table('items', function (Blueprint $table) {

            $table->string('currency_type_id')->change();
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');

        });
    }
}
