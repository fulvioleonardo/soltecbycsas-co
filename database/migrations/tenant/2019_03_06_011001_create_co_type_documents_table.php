<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoTypeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_type_documents', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('code', 3)->unique();
            $table->char('template');
            $table->string('resolution_number')->nullable();
            $table->date('resolution_date')->nullable();
            $table->string('technical_key')->nullable();
            $table->char('prefix')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
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
        Schema::dropIfExists('co_type_documents');
    }
}
