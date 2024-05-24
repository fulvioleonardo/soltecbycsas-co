<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTypeLiabilityToPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->unsignedInteger('type_obligation_id')->nullable()->after('perception_agent');
            $table->foreign('type_obligation_id')->references('id')->on('co_type_obligations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign(['person_type_id']);
            $table->dropColumn('person_type_id');
        });
    }
}
