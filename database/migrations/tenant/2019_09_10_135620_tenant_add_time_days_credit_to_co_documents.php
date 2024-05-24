<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTimeDaysCreditToCoDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_documents', function (Blueprint $table) {
            $table->tinyInteger('time_days_credit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_documents', function (Blueprint $table) {
            //
            $table->dropColumn('time_days_credit');
        });
    }
}
