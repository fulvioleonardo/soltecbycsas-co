<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDocumentPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents_pos', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->uuid('external_id');
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');
            $table->char('soap_type_id', 2);
            $table->char('state_type_id', 2);
            $table->char('prefix', 5);
            $table->string('series')->nullable();
            $table->string('number')->nullable();
            $table->unsignedInteger('currency_id');
            $table->decimal('sale', 10 ,2);
            $table->json('taxes');
            $table->decimal('total_tax', 10 ,2);
            $table->decimal('subtotal', 10 ,2);
            $table->date('date_of_issue');
            $table->time('time_of_issue');
            $table->unsignedInteger('customer_id');
            $table->json('customer');
            $table->decimal('exchange_rate_sale', 13, 3);
            $table->boolean('apply_concurrency')->index()->default(false);
            $table->boolean('enabled_concurrency')->index()->default(false);
            $table->date('automatic_date_of_issue')->index()->nullable();
            $table->decimal('total_discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('filename')->nullable();
            $table->string('changed')->default(false);
            $table->boolean('paid')->default(false);

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('currency_id')->references('id')->on('co_currencies');
            $table->foreign('customer_id')->references('id')->on('persons');

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents_pos');
    }
}
