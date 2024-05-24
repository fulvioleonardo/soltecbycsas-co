<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDeleteUniqueFilenameToRemissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_remissions', function (Blueprint $table) {
            $table->dropUnique(['filename']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_remissions', function (Blueprint $table) {
            $table->unique('filename');
        });
    }
}
