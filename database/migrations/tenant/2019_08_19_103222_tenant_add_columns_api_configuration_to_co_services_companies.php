<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsApiConfigurationToCoServicesCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_service_companies', function (Blueprint $table) {
            $table->string('id_software', 100)->nullable();
            $table->string('pin_software', 20)->nullable();
            $table->text('url_software')->nullable();
            $table->text('response_software')->nullable();

            $table->text('certificate_name')->nullable();
            $table->text('password_certificate')->nullable();
            $table->text('response_certificate')->nullable();
            
            $table->text('response_resolution')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_service_companies', function (Blueprint $table) {
            $table->dropColumn(['id_software', 'pin_software', 'url_software','response_software', 'certificate_name', 'password_certificate', 'response_certificate', 'response_resolution']);
        });
    }
}
