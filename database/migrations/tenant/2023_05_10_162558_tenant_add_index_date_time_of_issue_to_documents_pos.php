<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddIndexDateTimeOfIssueToDocumentsPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents_pos', function (Blueprint $table) {
            $table->index('date_of_issue');	
            $table->index('time_of_issue');	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents_pos', function (Blueprint $table) {
            $table->dropIndex(['date_of_issue']);	
            $table->dropIndex(['time_of_issue']);	
        });
    }
}
