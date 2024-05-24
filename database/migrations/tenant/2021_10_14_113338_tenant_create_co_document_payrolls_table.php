<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoDocumentPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_document_payrolls', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->uuid('external_id')->unique();
            $table->date('date_of_issue')->index();
            $table->time('time_of_issue')->index();

            $table->unsignedInteger('type_document_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');

            $table->text('head_note')->nullable();
            $table->text('foot_note')->nullable();

            $table->json('novelty')->nullable();

            $table->json('period');
            
            $table->string('prefix')->index();
            $table->bigInteger('consecutive')->index();

            $table->unsignedBigInteger('payroll_period_id');
            $table->text('notes')->nullable();
            
            $table->unsignedBigInteger('worker_id');
            $table->json('worker');
            
            $table->json('payment');
            $table->json('payment_dates');
            $table->json('response_api')->nullable();

            $table->foreign('type_document_id')->references('id')->on('co_type_documents');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('payroll_period_id')->references('id')->on('co_payroll_periods');
            $table->foreign('worker_id')->references('id')->on('co_workers');

            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_document_payrolls');
    }

}
