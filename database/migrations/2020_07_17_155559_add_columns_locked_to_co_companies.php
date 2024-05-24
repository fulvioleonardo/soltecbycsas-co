<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsLockedToCoCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_companies', function (Blueprint $table) {

            $table->integer('limit_users')->default(2)->after('economic_activity_code');
            $table->boolean('locked')->default(false)->after('limit_users');
            $table->boolean('locked_users')->default(false)->after('locked');
            $table->boolean('locked_tenant')->default(false)->after('locked');
            $table->boolean('locked_emission')->default(false)->after('locked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_companies', function (Blueprint $table) {
            $table->dropColumn('limit_users');
            $table->dropColumn('locked');
            $table->dropColumn('locked_users');
            $table->dropColumn('locked_tenant');
            $table->dropColumn('locked_emission');
        });
    }
}
