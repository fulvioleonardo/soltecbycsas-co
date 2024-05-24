<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsEmailToCoAdvancedConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_advanced_configuration', function (Blueprint $table) {

            $table->string('radian_imap_encryption')->default('ssl')->after('transportation_allowance');
            $table->string('radian_imap_host')->default('imap.gmail.com')->after('transportation_allowance');
            $table->string('radian_imap_port')->default('993')->after('transportation_allowance');
            $table->string('radian_imap_password')->nullable()->after('transportation_allowance');
            $table->string('radian_imap_user')->nullable()->after('transportation_allowance');

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

            $table->dropColumn('radian_imap_encryption'); 
            $table->dropColumn('radian_imap_host'); 
            $table->dropColumn('radian_imap_port'); 
            $table->dropColumn('radian_imap_password'); 
            $table->dropColumn('radian_imap_user'); 

        });
    }
}
