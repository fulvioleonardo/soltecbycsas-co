<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoServiceCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_service_companies', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
          //  $table->foreign('user_id')->references('id')->on('users');
            $table->string('identification_number')->unique();
            $table->char('dv', 1)->nullable();
            $table->unsignedBigInteger('language_id');
            $table->foreign('language_id')->references('id')->on('co_service_languages');
            $table->unsignedBigInteger('tax_id');
            $table->foreign('tax_id')->references('id')->on('co_service_taxes');
            $table->unsignedBigInteger('type_environment_id');
            $table->foreign('type_environment_id')->references('id')->on('co_service_type_environments');
            $table->unsignedBigInteger('type_operation_id');
            $table->foreign('type_operation_id')->references('id')->on('co_service_type_operations');
            $table->unsignedBigInteger('type_document_identification_id');
            $table->foreign('type_document_identification_id')->references('id')->on('co_service_type_document_identifications');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('co_service_countries');
            $table->unsignedBigInteger('type_currency_id');
            $table->foreign('type_currency_id')->references('id')->on('co_service_type_currencies');
            $table->unsignedBigInteger('type_organization_id');
            $table->foreign('type_organization_id')->references('id')->on('co_service_type_organizations');
            $table->unsignedBigInteger('type_regime_id');
            $table->foreign('type_regime_id')->references('id')->on('co_service_type_regimes');
            $table->unsignedBigInteger('type_liability_id');
            $table->foreign('type_liability_id')->references('id')->on('co_service_type_liabilities');
            $table->unsignedBigInteger('municipality_id');
            $table->foreign('municipality_id')->references('id')->on('co_service_municipalities');
            $table->string('merchant_registration');
            $table->string('address');
            $table->string('phone');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('co_service_companies');
    }
}
