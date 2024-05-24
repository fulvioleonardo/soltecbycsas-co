<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddShowEcommerceToPaymentMethodTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_method_types', function (Blueprint $table) {
            $table->boolean('show_ecommerce')->default(false)->index();
        });

        DB::table('payment_method_types')->where('id', '01')->update([
            'show_ecommerce' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_method_types', function (Blueprint $table) {
            $table->dropColumn('show_ecommerce');   
        });
    }
}
