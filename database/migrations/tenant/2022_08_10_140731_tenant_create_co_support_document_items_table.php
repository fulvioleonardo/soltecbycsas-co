<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoSupportDocumentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_support_document_items', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('co_support_document_id');
            $table->unsignedInteger('item_id');
            $table->json('item');
            $table->unsignedInteger('unit_type_id');
            $table->unsignedInteger('type_generation_transmition_id');
            $table->date('start_date');
            $table->decimal('quantity', 12, 4);
            $table->unsignedInteger('tax_id');
            $table->json('tax');
            $table->decimal('unit_price', 16, 6);
            $table->decimal('total_tax', 18 ,2);
            $table->decimal('subtotal', 18 ,2);
            $table->decimal('discount', 18 ,2);
            $table->decimal('total', 18, 2);

            $table->foreign('co_support_document_id')->references('id')->on('co_support_documents')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_type_id')->references('id')->on('co_type_units');
            $table->foreign('tax_id')->references('id')->on('co_taxes');
            $table->foreign('type_generation_transmition_id', 'fkspdi_type_generation_transmition_id')->references('id')->on('co_type_generation_transmitions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_support_document_items');
    }
    
}
