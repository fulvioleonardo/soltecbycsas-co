<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoDocumentPayrollDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_document_payroll_deductions', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('co_document_payroll_id');

            $table->unsignedBigInteger('eps_type_law_deductions_id');
            $table->decimal('eps_deduction', 18, 2);

            $table->unsignedBigInteger('pension_type_law_deductions_id');
            $table->decimal('pension_deduction', 18, 2);
 
            $table->json('labor_union')->nullable();
            $table->json('sanctions')->nullable();
            $table->json('orders')->nullable();
            $table->json('third_party_payments')->nullable();
            $table->json('advances')->nullable();
            $table->json('other_deductions')->nullable();
             
            $table->decimal('voluntary_pension', 18, 2)->nullable();
            $table->decimal('withholding_at_source', 18, 2)->nullable();
            $table->decimal('afc', 18, 2)->nullable();
            $table->decimal('cooperative', 18, 2)->nullable();
            $table->decimal('tax_liens', 18, 2)->nullable();
            $table->decimal('supplementary_plan', 18, 2)->nullable();

            $table->decimal('education', 18, 2)->nullable();
            $table->decimal('refund', 18, 2)->nullable();
            $table->decimal('debt', 18, 2)->nullable();

            $table->decimal('deductions_total', 18, 2);

            $table->foreign('eps_type_law_deductions_id', 'd_p_d_eps_type_law_deductions_id_foreign')->references('id')->on('co_type_law_deductions');
            $table->foreign('pension_type_law_deductions_id', 'd_p_d_pension_type_law_deductions_id_foreign')->references('id')->on('co_type_law_deductions');
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
        Schema::dropIfExists('co_document_payroll_deductions');
    }

}
