<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            
            $table->unsignedInteger('currency_id')->after('prefix');
            $table->foreign('currency_id')->references('id')->on('co_currencies');

            $table->json('taxes')->after('currency_id');

            $table->dropForeign(['currency_type_id']);	
            $table->dropColumn(['currency_type_id']);

            $table->dropColumn(['exchange_rate_sale']);

            $table->decimal('sale', 12 ,2)->after('taxes');
            $table->decimal('total_tax', 12 ,2)->after('taxes');
            $table->decimal('subtotal', 12 ,2)->after('total_tax');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {

            $table->dropForeign(['currency_id']);	
            $table->dropColumn(['currency_id']);

            $table->dropColumn(['taxes']);

            $table->string('currency_type_id');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');

            $table->decimal('exchange_rate_sale', 13, 3);

            $table->dropColumn(['sale', 'total_tax', 'subtotal']);

        });
    }
}
