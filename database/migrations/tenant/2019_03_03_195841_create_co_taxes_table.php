<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_taxes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('code', 2)->nullable();
            $table->decimal('rate', 10, 2)->default(0);
            $table->decimal('conversion', 10, 2)->default(100);
            $table->boolean('is_percentage')->default(true);
            $table->boolean('is_fixed_value')->default(false);
            $table->boolean('is_retention')->default(false);
            $table->boolean('in_base')->default(false);
            $table->unsignedInteger('in_tax')->nullable();
            $table->foreign('in_tax')->references('id')->on('co_taxes');
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
        Schema::dropIfExists('co_taxes');
    }
}
