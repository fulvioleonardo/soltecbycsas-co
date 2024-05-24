<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoSupportDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_support_documents', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->uuid('external_id');
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');

            $table->unsignedInteger('supplier_id');
            $table->json('supplier');
            
            $table->unsignedBigInteger('type_environment_id');
            $table->unsignedInteger('state_document_id');
            $table->date('date_of_issue')->index();
            $table->time('time_of_issue');
            $table->unsignedInteger('currency_id');
            $table->unsignedInteger('type_document_id');
            $table->string('prefix');
            $table->bigInteger('number');

            $table->unsignedBigInteger('payment_form_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->tinyInteger('time_days_credit');

            $table->decimal('sale', 18 ,2);
            $table->decimal('total_tax', 18 ,2);
            $table->decimal('subtotal', 18 ,2);
            $table->decimal('total_discount', 18 ,2);
            $table->decimal('total', 18, 2);

            $table->string('acknowledgment_received')->nullable();
            $table->string('cufe')->nullable();
            $table->string('xml')->nullable();
            $table->date('date_expiration')->nullable();
            $table->string('observation')->nullable();
            $table->json('taxes');
            $table->json('response_api')->nullable();

            $table->unique(['prefix', 'number']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('supplier_id')->references('id')->on('persons');
            $table->foreign('state_document_id')->references('id')->on('co_state_documents');
            $table->foreign('type_document_id')->references('id')->on('co_type_documents');
            $table->foreign('currency_id')->references('id')->on('co_currencies');
            $table->foreign('payment_form_id')->references('id')->on('co_payment_forms');
            $table->foreign('payment_method_id')->references('id')->on('co_payment_methods');
            $table->foreign('type_environment_id')->references('id')->on('co_service_type_environments');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_support_documents');
    }
}
