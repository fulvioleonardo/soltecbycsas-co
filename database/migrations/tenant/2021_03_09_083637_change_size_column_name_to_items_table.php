<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizeColumnNameToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('description', 500)->nullable()->change();
            $table->string('name', 500)->nullable()->change();
            $table->string('second_name', 500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->change();
            $table->string('name', 255)->nullable()->change();
            $table->string('second_name', 255)->nullable()->change();
        });
    }
}
