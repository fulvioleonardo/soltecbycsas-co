<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoServiceDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_service_departments', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('co_service_countries');
            $table->string('name');
            $table->char('code');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('co_service_departments');
    }
}
