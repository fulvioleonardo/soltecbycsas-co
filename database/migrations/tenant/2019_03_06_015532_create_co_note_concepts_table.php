<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoNoteConceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_note_concepts', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_document_id')->default(1);
            $table->foreign('type_document_id')->references('id')->on('co_type_documents');
            $table->string('name');
            $table->char('code', 1);
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
        Schema::dropIfExists('co_note_concepts');
    }
}
