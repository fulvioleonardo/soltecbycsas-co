<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_cities', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('co_departments');
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
        Schema::dropIfExists('co_cities');
    }
}
