<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCoSupportDocumentAdjustNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_support_document_adjust_notes', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('co_support_document_id')->comment('relacion con documento de ajuste');
            $table->unsignedInteger('affected_support_document_id')->comment('relacion con documento afectado');
            $table->unsignedInteger('note_concept_id');
            $table->string('discrepancy_response_description', 500);
            
            $table->foreign('note_concept_id')->references('id')->on('co_note_concepts');
            $table->foreign('affected_support_document_id', 'affected_support_document_id_fk')->references('id')->on('co_support_documents');
            $table->foreign('co_support_document_id', 'co_support_document_id_fk')->references('id')->on('co_support_documents')->onDelete('cascade');
            
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_support_document_adjust_notes');
    }
}
