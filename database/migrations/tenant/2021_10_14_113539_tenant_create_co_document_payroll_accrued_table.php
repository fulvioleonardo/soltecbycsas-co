<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoDocumentPayrollAccruedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_document_payroll_accrued', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('co_document_payroll_id');
            $table->integer('worked_days');
            $table->decimal('salary', 18, 2);
            $table->decimal('transportation_allowance', 18, 2)->nullable();

            $table->json('heds')->nullable();
            $table->json('hens')->nullable();
            $table->json('hrns')->nullable();
            $table->json('heddfs')->nullable();
            $table->json('hrddfs')->nullable();
            $table->json('hendfs')->nullable();
            $table->json('hrndfs')->nullable();

            $table->json('common_vacation')->nullable();
            $table->json('paid_vacation')->nullable();
            $table->json('service_bonus')->nullable();
            $table->json('severance')->nullable();

            $table->json('work_disabilities')->nullable();
            $table->json('maternity_leave')->nullable();
            $table->json('paid_leave')->nullable();
            $table->json('non_paid_leave')->nullable();

            $table->json('bonuses')->nullable();
            $table->json('aid')->nullable();
            $table->json('legal_strike')->nullable();
            $table->json('other_concepts')->nullable();

            $table->json('compensations')->nullable();
            $table->json('epctv_bonuses')->nullable();
            $table->json('commissions')->nullable();
            $table->json('third_party_payments')->nullable();
            $table->json('advances')->nullable();
            
            $table->decimal('endowment', 18, 2)->nullable();
            $table->decimal('sustenance_support', 18, 2)->nullable();
            $table->decimal('telecommuting', 18, 2)->nullable();
            $table->decimal('withdrawal_bonus', 18, 2)->nullable();
            $table->decimal('compensation', 18, 2)->nullable();
            $table->decimal('accrued_total', 18, 2)->nullable();

            $table->foreign('co_document_payroll_id')->references('id')->on('co_document_payrolls')->onDelete('cascade');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_document_payroll_accrued');
    }
}
