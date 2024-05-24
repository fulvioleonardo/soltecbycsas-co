<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddQRFieldToDocumentPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents_pos', function (Blueprint $table) {
            $table->string('qr')->nullable()->after('electronic');
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
            $table->dropColumn('qr');
        });
    }
}
