<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoRemissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_remissions', function (Blueprint $table) {

            $table->increments('id'); 
            $table->unsignedInteger('user_id');
            $table->uuid('external_id');
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');
            $table->char('state_type_id', 2);

            $table->date('date_of_issue')->index();
            $table->time('time_of_issue');

            $table->unsignedInteger('customer_id');
            $table->json('customer');

            $table->string('prefix');
            $table->bigInteger('number');
            $table->unsignedInteger('currency_id');

            $table->date('date_expiration')->nullable();
            $table->string('observation')->nullable();

            $table->decimal('sale', 18 ,2);
            $table->decimal('total_tax', 18 ,2);
            $table->json('taxes');
            $table->decimal('total_discount', 18 ,2);
            $table->decimal('subtotal', 18 ,2);
            $table->decimal('total', 18 ,2);

            $table->unsignedBigInteger('payment_form_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->tinyInteger('time_days_credit')->nullable();
            $table->string('filename')->nullable()->unique();

            $table->timestamps();
            
            $table->index(['prefix', 'number']);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('customer_id')->references('id')->on('persons');
            $table->foreign('state_type_id')->references('id')->on('state_types');
            $table->foreign('payment_method_id')->references('id')->on('co_payment_methods');
            $table->foreign('payment_form_id')->references('id')->on('co_payment_forms');
            $table->foreign('currency_id')->references('id')->on('co_currencies');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_remissions');
    }

}
