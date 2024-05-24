<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoDocumentPayrollAdjustNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_document_payroll_adjust_notes', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('co_document_payroll_id')->comment('relacion con nomina de eliminacion o ajuste');
            $table->unsignedBigInteger('type_payroll_adjust_note_id');
            $table->unsignedBigInteger('affected_document_payroll_id')->comment('relacion con nomina afectada');


            $table->foreign('type_payroll_adjust_note_id', 'type_payroll_adjust_note_id_fk')->references('id')->on('co_type_payroll_adjust_notes');
            $table->foreign('affected_document_payroll_id', 'affected_document_payroll_id_fk')->references('id')->on('co_document_payrolls');
            $table->foreign('co_document_payroll_id', 'co_document_payroll_id_fk')->references('id')->on('co_document_payrolls')->onDelete('cascade');
            
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_document_payroll_adjust_notes');
    }

}
