<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //solvent problem by column type enum
        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('persons', function (Blueprint $table) {
            
            $table->dropForeign(['identity_document_type_id']);	
            $table->dropForeign(['country_id']);	
            $table->dropForeign(['department_id']);	

            $table->unsignedInteger('type_person_id')->nullable()->after('type');
            $table->foreign('type_person_id')->references('id')->on('co_type_people');

            $table->unsignedInteger('type_regime_id')->nullable()->after('type');
            $table->foreign('type_regime_id')->references('id')->on('co_type_regimes');
            
            $table->unsignedInteger('city_id')->nullable()->after('department_id');
            $table->foreign('city_id')->references('id')->on('co_cities');

            $table->string('code')->after('type');
            $table->char('dv', 2)->after('type')->nullable();

        });

        Schema::table('persons', function (Blueprint $table) {
            
            $table->unsignedInteger('identity_document_type_id')->change();
            $table->foreign('identity_document_type_id')->references('id')->on('co_type_identity_documents');

            $table->unsignedInteger('country_id')->nullable()->change();
            $table->foreign('country_id')->references('id')->on('co_countries');
            
            $table->unsignedInteger('department_id')->nullable()->change();
            $table->foreign('department_id')->references('id')->on('co_departments');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::connection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

        Schema::table('persons', function (Blueprint $table) {

            $table->dropForeign(['identity_document_type_id']);	
            $table->dropForeign(['country_id']);	
            $table->dropForeign(['department_id']);	

            $table->dropForeign(['type_person_id']);	
            $table->dropColumn(['type_person_id']);	

            $table->dropForeign(['type_regime_id']);	
            $table->dropColumn(['type_regime_id']);	
            
            $table->dropForeign(['city_id']);	
            $table->dropColumn(['city_id']);

            $table->dropColumn(['code']);
            $table->dropColumn(['dv']);

        });

        Schema::table('persons', function (Blueprint $table) {

            $table->string('identity_document_type_id')->change();
            $table->foreign('identity_document_type_id')->references('id')->on('cat_identity_document_types');

            $table->dropColumn(['country_id']);
            $table->dropColumn(['department_id']);

        });

        Schema::table('persons', function (Blueprint $table) {

            $table->char('country_id', 2)->after('trade_name');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->char('department_id', 2)->after('country_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');

        });

    }
}
