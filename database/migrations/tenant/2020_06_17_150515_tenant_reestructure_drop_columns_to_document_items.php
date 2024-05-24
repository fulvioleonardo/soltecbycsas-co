<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureDropColumnsToDocumentItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_items', function (Blueprint $table) {
            
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

            $table->dropColumn(['attributes', 'discounts', 'charges', 'additional_information', 'name_product_pdf']);	

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
            $table->text('additional_information')->nullable()->after('charges');
            $table->string('name_product_pdf')->nullable();

        });
    }
}
