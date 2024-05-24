<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            
            $table->unsignedInteger('currency_id')->after('number');
            $table->foreign('currency_id')->references('id')->on('co_currencies');
            
            $table->decimal('sale', 10 ,2)->after('currency_id');
            $table->json('taxes')->after('sale');
            $table->decimal('total_tax', 10 ,2)->after('taxes');
            $table->decimal('subtotal', 10 ,2)->after('total_tax');

            $table->dropForeign(['currency_type_id']);	
            $table->dropColumn(['currency_type_id']);

            $table->dropColumn(['total_prepayment']);
            $table->dropColumn(['total_charge']);
            $table->dropColumn(['total_exportation']);
            $table->dropColumn(['total_free']);
            $table->dropColumn(['total_taxed']);
            $table->dropColumn(['total_unaffected']);
            $table->dropColumn(['total_exonerated']);
            $table->dropColumn(['total_igv']);
            $table->dropColumn(['total_base_isc']);
            $table->dropColumn(['total_isc']);
            $table->dropColumn(['total_base_other_taxes']);
            $table->dropColumn(['total_other_taxes']);
            $table->dropColumn(['total_taxes']);
            $table->dropColumn(['total_value']);

            $table->dropColumn(['charges','discounts','prepayments','guides','related','perception','detraction','legends']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            

            $table->dropForeign(['currency_id']);	
            $table->dropColumn(['currency_id']);
            $table->dropColumn(['sale', 'taxes', 'total_tax', 'subtotal']);

            $table->string('currency_type_id');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');

            $table->decimal('total_prepayment', 12, 2)->default(0);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_exportation', 12, 2)->default(0);
            $table->decimal('total_free', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2)->default(0);
            $table->decimal('total_unaffected', 12, 2)->default(0);
            $table->decimal('total_exonerated', 12, 2)->default(0);
            $table->decimal('total_igv', 12, 2)->default(0);
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);
            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);

            $table->json('charges')->nullable();
            $table->json('discounts')->nullable();
            $table->json('prepayments')->nullable();
            $table->json('guides')->nullable();
            $table->json('related')->nullable();
            $table->json('perception')->nullable();
            $table->json('detraction')->nullable();
            $table->json('legends')->nullable();

        });
    }
}
