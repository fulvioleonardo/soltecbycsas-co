<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeObservationToDocuments extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('observation', 2048)->change();
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('observation', 191)->change();
        });
    }
}
