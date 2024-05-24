<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddUvtToCoAdvancedConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_advanced_configuration', function (Blueprint $table) {
            $table->decimal('uvt', 18, 2)->default(0)->after('radian_imap_encryption');
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
            $table->dropColumn('uvt'); 
        });
    }
}
