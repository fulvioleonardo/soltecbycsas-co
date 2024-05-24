<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCoWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_workers', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('code')->index();
            $table->unsignedBigInteger('type_worker_id');
            $table->unsignedBigInteger('sub_type_worker_id');
            $table->unsignedBigInteger('payroll_type_document_identification_id');
            $table->unsignedBigInteger('municipality_id');
            $table->unsignedBigInteger('type_contract_id');

            $table->string('identification_number')->index();
            $table->string('surname')->index();
            $table->string('second_surname')->index();
            $table->string('first_name')->index();
            $table->string('address');

            $table->boolean('high_risk_pension')->default(false);
            $table->boolean('integral_salarary')->default(false);
            $table->decimal('salary', 18, 2);

            $table->foreign('type_worker_id')->references('id')->on('co_type_workers');
            $table->foreign('sub_type_worker_id')->references('id')->on('co_sub_type_workers');
            $table->foreign('payroll_type_document_identification_id')->references('id')->on('co_payroll_type_document_identifications');
            $table->foreign('municipality_id')->references('id')->on('co_service_municipalities');
            $table->foreign('type_contract_id')->references('id')->on('co_type_contracts');

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
        Schema::dropIfExists('co_workers');
    }

}
