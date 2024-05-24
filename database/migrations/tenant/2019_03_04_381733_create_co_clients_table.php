<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_clients', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_person_id')->nullable();
            $table->foreign('type_person_id')->references('id')->on('co_type_people');
            $table->unsignedInteger('type_regime_id')->nullable();
            $table->foreign('type_regime_id')->references('id')->on('co_type_regimes');
            $table->unsignedInteger('type_identity_document_id');
            $table->foreign('type_identity_document_id')->references('id')->on('co_type_identity_documents');
            $table->string('identification_number');
            $table->string('name');
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('co_countries');
            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('co_departments');
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('co_cities');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('co_clients');
    }
}
