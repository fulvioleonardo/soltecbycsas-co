<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDocumentPosItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_pos_items', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('document_pos_id');
            $table->unsignedInteger('item_id');
            $table->json('item');
            $table->unsignedInteger('unit_type_id');
            $table->unsignedInteger('tax_id')->nullable();
            $table->json('tax')->nullable();

            $table->decimal('total_tax', 10 ,2);
            $table->decimal('subtotal', 10 ,2);
            $table->decimal('discount', 10 ,2);
            $table->decimal('quantity',12,4);
            $table->decimal('unit_price', 16, 6);
            $table->decimal('total', 12, 2);
            $table->unsignedInteger('inventory_kardex_id')->nullable();


            $table->foreign('document_pos_id')->references('id')->on('documents_pos')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_type_id')->references('id')->on('co_type_units');
            $table->foreign('tax_id')->references('id')->on('co_taxes');
            $table->foreign('inventory_kardex_id')->references('id')->on('inventory_kardex');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents_pos_items');
    }
}
