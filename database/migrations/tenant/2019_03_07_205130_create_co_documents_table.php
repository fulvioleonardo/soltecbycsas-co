<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_documents', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_document_id')->default(1);
            $table->foreign('state_document_id')->references('id')->on('co_state_documents');
            $table->unsignedInteger('type_document_id');
            $table->foreign('type_document_id')->references('id')->on('co_type_documents');
            $table->char('prefix')->nullable();
            $table->string('number');
            $table->string('xml')->nullable();
            $table->string('cufe')->nullable();
            $table->unique(['prefix', 'number']);
            $table->unsignedInteger('type_invoice_id')->nullable();
            $table->foreign('type_invoice_id')->references('id')->on('co_type_invoices');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('co_clients');
            $table->json('client');
            $table->unsignedInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('co_currencies');
            $table->dateTime('date_issue');
            $table->unsignedInteger('reference_id')->nullable();
            $table->foreign('reference_id')->references('id')->on('co_documents');
            $table->unsignedInteger('note_concept_id')->nullable();
            $table->foreign('note_concept_id')->references('id')->on('co_note_concepts');
            $table->decimal('sale', 10, 2);
            $table->decimal('total_discount', 10, 2);
            $table->json('taxes');
            $table->decimal('total_tax', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->unsignedInteger('version_ubl_id');
            $table->foreign('version_ubl_id')->references('id')->on('co_version_ubls');
            $table->unsignedInteger('ambient_id');
            $table->foreign('ambient_id')->references('id')->on('co_ambients');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('co_documents');
    }
}
