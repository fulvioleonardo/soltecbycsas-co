<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddElectronicFieldsToDocumentPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents_pos', function (Blueprint $table) {
            $table->boolean('electronic')->default(false)->after('paid');
            $table->string('cude')->nullable()->after('electronic');
            $table->json('request_api')->nullable()->after('cude');
            $table->json('response_api')->nullable()->after('request_api');
            $table->unsignedInteger('ambient_id')->nullable()->after('response_api');
            $table->unsignedInteger('note_concept_id')->nullable()->after('ambient_id');
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
            $table->dropColumn('electronic');
            $table->dropColumn('cude');
            $table->dropColumn('request_api');
            $table->dropColumn('response_api');
            $table->dropColumn('ambient_id');
            $table->dropColumn('note_concept_id');
        });
    }
}
