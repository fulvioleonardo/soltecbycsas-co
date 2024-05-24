<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoDetailQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_detail_quotations', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('quotation_id');
            $table->foreign('quotation_id')->references('id')->on('co_quotations');
            $table->unsignedInteger('item_id');
            $table->foreign('item_id')->references('id')->on('co_items');
            $table->json('item');
            $table->unsignedInteger('type_unit_id');
            $table->foreign('type_unit_id')->references('id')->on('co_type_units');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('co_taxes');
            $table->json('tax')->nullable();
            $table->decimal('total_tax', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('co_detail_quotations');
    }
}
