<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeQrFieldToDocumentsPos extends Migration
{
    public function up()
    {
        Schema::table('documents_pos', function (Blueprint $table) {
            $table->string('qr', 512)->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('documents_pos', function (Blueprint $table) {
            $table->string('qr', 255)->nullable()->default(null)->change();
        });
    }
}
