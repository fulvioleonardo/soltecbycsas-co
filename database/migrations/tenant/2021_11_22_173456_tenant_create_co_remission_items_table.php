<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoRemissionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_remission_items', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('remission_id');
            $table->unsignedInteger('item_id');
            $table->json('item');
            
            $table->unsignedInteger('unit_type_id');
            $table->unsignedInteger('tax_id')->nullable();
            $table->json('tax')->nullable();

            $table->decimal('total_tax', 18 ,2);
            $table->decimal('subtotal', 18 ,2);
            $table->decimal('discount', 18 ,2);
            $table->decimal('quantity', 12, 4);

            $table->decimal('unit_price', 16, 6);
            $table->decimal('total', 18, 2);

            $table->foreign('remission_id')->references('id')->on('co_remissions')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_type_id')->references('id')->on('co_type_units');
            $table->foreign('tax_id')->references('id')->on('co_taxes');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_remission_items');
    }

}
