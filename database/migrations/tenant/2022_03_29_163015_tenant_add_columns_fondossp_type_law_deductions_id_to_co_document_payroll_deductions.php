<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsFondosspTypeLawDeductionsIdToCoDocumentPayrollDeductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_document_payroll_deductions', function (Blueprint $table) {

            $table->decimal('fondosp_deduction_SP', 18, 2)->nullable()->after('pension_type_law_deductions_id');
            $table->unsignedBigInteger('fondossp_type_law_deductions_id')->nullable()->after('pension_type_law_deductions_id');

            $table->decimal('fondosp_deduction_sub', 18, 2)->nullable()->after('pension_type_law_deductions_id');
            $table->unsignedBigInteger('fondossp_sub_type_law_deductions_id')->nullable()->after('pension_type_law_deductions_id');

            $table->foreign('fondossp_type_law_deductions_id', 'f_sp_t_law_deductions_id_foreign')->references('id')->on('co_type_law_deductions');
            $table->foreign('fondossp_sub_type_law_deductions_id', 'f_sp_sub_t_law_deductions_id_foreign')->references('id')->on('co_type_law_deductions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_document_payroll_deductions', function (Blueprint $table) {

            $table->dropForeign('f_sp_t_law_deductions_id_foreign');
            $table->dropForeign('f_sp_sub_t_law_deductions_id_foreign');

            $table->dropColumn('fondosp_deduction_SP'); 
            $table->dropColumn('fondossp_type_law_deductions_id'); 
            $table->dropColumn('fondosp_deduction_sub'); 
            $table->dropColumn('fondossp_sub_type_law_deductions_id'); 

        });
    }
}
