<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddLastSyncToCoAdvancedConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_advanced_configuration', function (Blueprint $table) {
            $table->unsignedBigInteger('lastsync')->default(0)->after('uvt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_advanced_configuration', function (Blueprint $table) {
            $table->dropColumn('lastsync');
        });
    }
}
