<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToExpenses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {

            $table->unsignedInteger('currency_id')->after('number');
            $table->foreign('currency_id')->references('id')->on('co_currencies');
            
            $table->dropForeign(['currency_type_id']);	
            $table->dropColumn(['currency_type_id']);

            $table->dropColumn(['exchange_rate_sale']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {

            $table->dropForeign(['currency_id']);	
            $table->dropColumn(['currency_id']);

            $table->string('currency_type_id');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');

            $table->decimal('exchange_rate_sale', 13, 3);

        });
    }
}
