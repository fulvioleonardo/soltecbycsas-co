<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToEstablishments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('establishments', function (Blueprint $table) {

            $table->dropForeign(['country_id']);	
            $table->dropForeign(['department_id']);
            
            $table->unsignedInteger('city_id')->nullable()->after('department_id');
            $table->foreign('city_id')->references('id')->on('co_cities');

        });


        Schema::table('establishments', function (Blueprint $table) {

            $table->dropColumn(['country_id']);	
            $table->dropColumn(['department_id']);

        });


        Schema::table('establishments', function (Blueprint $table) {
            
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('co_countries');
            
            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('co_departments');

            $table->dropForeign(['province_id']);	
            $table->dropColumn(['province_id']);

            $table->dropForeign(['district_id']);	
            $table->dropColumn(['district_id']);

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('establishments', function (Blueprint $table) {

            $table->char('province_id', 4)->nullable();
            $table->char('district_id', 6)->nullable();
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('district_id')->references('id')->on('districts');

            $table->dropForeign(['country_id']);	
            $table->dropForeign(['department_id']);	

            $table->dropForeign(['city_id']);	
            $table->dropColumn(['city_id']);

        });

        Schema::table('establishments', function (Blueprint $table) {

            $table->dropColumn(['country_id']);
            $table->dropColumn(['department_id']);

        });
 

        Schema::table('establishments', function (Blueprint $table) {

            $table->char('country_id', 2)->nullable();
            $table->foreign('country_id')->references('id')->on('countries');

            $table->char('department_id', 2)->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

        });

    }
}
