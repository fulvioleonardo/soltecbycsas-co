<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddPaymentMethodTypeIdToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->char('payment_method_type_id', 2)->nullable()->after('reference_payment');
            $table->foreign('payment_method_type_id')->references('id')->on('payment_method_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_method_type_id']);
            $table->dropColumn('payment_method_type_id');   
        });
    }
}
