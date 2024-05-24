<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoCompaniesPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_companies_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('companie_id');
            $table->date('date_of_payment');
            $table->unsignedInteger('payment_method_type_id');
            $table->boolean('has_card')->default(false);
            $table->unsignedInteger('card_brand_id')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('payment', 12, 2);
            $table->boolean('state')->default(false);
            $table->timestamps();

            $table->foreign('companie_id')->references('id')->on('co_companies')->onDelete('cascade');
            $table->foreign('card_brand_id')->references('id')->on('card_brands');
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
        Schema::dropIfExists('co_companies_payments');
    }
}
