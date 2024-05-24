<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddFiltersToCoEmailReading extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_email_reading', function (Blueprint $table) {
            $table->date('search_end_date')->nullable()->index()->after('imap_server');
            $table->date('search_start_date')->nullable()->index()->after('imap_server');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_email_reading', function (Blueprint $table) {
            $table->dropColumn('search_end_date'); 
            $table->dropColumn('search_start_date'); 
        });
    }
}
