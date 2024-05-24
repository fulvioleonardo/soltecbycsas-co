<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantRegularizeFilenameToRemissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("UPDATE co_remissions  SET filename = CONCAT(prefix, '-', number, '-', DATE_FORMAT(date_of_issue, '%Y%m%d'))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("UPDATE co_remissions  SET filename = CONCAT(prefix, '-', id, '-', DATE_FORMAT(date_of_issue, '%Y%m%d'))");
    }

}
