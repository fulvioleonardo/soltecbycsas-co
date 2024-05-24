<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_companies', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_identity_document_id')->nullable();
            $table->foreign('type_identity_document_id')->references('id')->on('co_type_identity_documents');
            $table->string('identification_number')->unique();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('email');
            $table->string('subdomain', 10)->unique();
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('co_countries');
            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('co_departments');
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('co_cities');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('co_currencies');
            $table->unsignedInteger('type_regime_id')->nullable();
            $table->foreign('type_regime_id')->references('id')->on('co_type_regimes');
            $table->unsignedInteger('type_obligation_id')->nullable();
            $table->foreign('type_obligation_id')->references('id')->on('co_type_obligations');
            $table->unsignedInteger('version_ubl_id')->nullable();
            $table->foreign('version_ubl_id')->references('id')->on('co_version_ubls');
            $table->unsignedInteger('ambient_id')->nullable();
            $table->foreign('ambient_id')->references('id')->on('co_ambients');
            $table->string('software_identifier')->nullable();
            $table->string('software_password')->nullable();
            $table->string('pin')->nullable();
            $table->string('certificate_name')->nullable();
            $table->string('certificate_password')->nullable();
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
        Schema::dropIfExists('co_companies');
    }
}
