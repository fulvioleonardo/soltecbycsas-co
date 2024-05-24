<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeTaxIdToItems extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('tax_id')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('tax_id')->change();
        });
    }
}
