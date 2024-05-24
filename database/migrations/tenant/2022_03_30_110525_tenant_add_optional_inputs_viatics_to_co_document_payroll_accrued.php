<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddOptionalInputsViaticsToCoDocumentPayrollAccrued extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_document_payroll_accrued', function (Blueprint $table) {
            $table->decimal('salary_viatics', 18, 2)->nullable()->after('compensation');
            $table->decimal('non_salary_viatics', 18, 2)->nullable()->after('compensation');
            $table->decimal('refund', 18, 2)->nullable()->after('compensation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_document_payroll_accrued', function (Blueprint $table) {
            $table->dropColumn('salary_viatics'); 
            $table->dropColumn('non_salary_viatics'); 
            $table->dropColumn('refund'); 
        });
    }
}
