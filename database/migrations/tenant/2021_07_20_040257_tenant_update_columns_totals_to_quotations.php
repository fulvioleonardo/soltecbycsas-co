<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantUpdateColumnsTotalsToQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('sale', 18, 2)->change();
            $table->decimal('subtotal', 18, 2)->change();
            $table->decimal('total_discount', 18, 2)->change();
            $table->decimal('total_tax', 18, 2)->change();
            $table->decimal('total', 18, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->decimal('sale', 10, 2)->change();
            $table->decimal('subtotal', 10, 2)->change();
            $table->decimal('total_discount', 10, 2)->change();
            $table->decimal('total_tax', 10, 2)->change();
            $table->decimal('total', 10, 2)->change();
        });
    }
}
