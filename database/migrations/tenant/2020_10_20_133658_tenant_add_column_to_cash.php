<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnToCash extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash', function (Blueprint $table) {
            $table->unsignedInteger('resolution_id')->after('reference_number')->nullable();
            $table->foreign('resolution_id')->references('id')->on('configuration_pos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash', function (Blueprint $table) {
            $table->dropColumn('resolution_id');
        });
    }
}
