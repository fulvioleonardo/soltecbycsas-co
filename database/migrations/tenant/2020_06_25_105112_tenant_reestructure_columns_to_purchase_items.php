<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToPurchaseItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {

            $table->unsignedInteger('unit_type_id')->after('item');
            $table->foreign('unit_type_id')->references('id')->on('co_type_units');

            $table->unsignedInteger('tax_id')->nullable()->after('unit_type_id');
            $table->foreign('tax_id')->references('id')->on('co_taxes');
            
            $table->json('tax')->nullable()->after('tax_id');

            $table->decimal('total_tax', 10 ,2)->after('tax');
            $table->decimal('subtotal', 10 ,2)->after('total_tax');
            $table->decimal('discount', 10 ,2)->after('subtotal');

            $table->dropColumn(['unit_value']);	
            
            $table->dropForeign(['affectation_igv_type_id']);	
            $table->dropColumn(['affectation_igv_type_id']);

            $table->dropColumn(['total_base_igv']);
            $table->dropColumn(['percentage_igv']);
            $table->dropColumn(['total_igv']);

            $table->dropForeign(['system_isc_type_id']);	
            $table->dropColumn(['system_isc_type_id']);

            $table->dropColumn(['total_base_isc']);
            $table->dropColumn(['percentage_isc']);
            $table->dropColumn(['total_isc']);

            $table->dropColumn(['total_base_other_taxes']);
            $table->dropColumn(['percentage_other_taxes']);
            $table->dropColumn(['total_other_taxes']);
            $table->dropColumn(['total_taxes']);

            $table->dropForeign(['price_type_id']);	
            $table->dropColumn(['price_type_id']);

            $table->dropColumn(['total_value', 'total_charge', 'total_discount']);	

            $table->dropColumn(['attributes', 'discounts', 'charges']);	

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            
            $table->dropForeign(['unit_type_id']);	
            $table->dropColumn(['unit_type_id']);	

            $table->dropForeign(['tax_id']);	
            $table->dropColumn(['tax_id']);
            
            $table->dropColumn(['tax','total_tax','subtotal','discount']);

 
            $table->decimal('unit_value', 16, 6);

            $table->string('affectation_igv_type_id');
            $table->foreign('affectation_igv_type_id')->references('id')->on('cat_affectation_igv_types');
            
            $table->decimal('total_base_igv', 12, 2);
            $table->decimal('percentage_igv', 12, 2);
            $table->decimal('total_igv', 12, 2);

            $table->string('system_isc_type_id')->nullable();
            $table->foreign('system_isc_type_id')->references('id')->on('cat_system_isc_types');

            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('percentage_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);

            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('percentage_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2);

            $table->string('price_type_id');
            $table->foreign('price_type_id')->references('id')->on('cat_price_types');
            
            $table->decimal('total_value', 12, 2);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_discount', 12, 2)->default(0);

            $table->json('attributes')->nullable();
            $table->json('discounts')->nullable();
            $table->json('charges')->nullable();

        });

        

    }
}
