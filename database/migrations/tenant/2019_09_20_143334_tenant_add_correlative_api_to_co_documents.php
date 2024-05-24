<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddCorrelativeApiToCoDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_documents', function (Blueprint $table) {
            $table->bigInteger('correlative_api')->nullable();
            $table->mediumText('response_api_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_documents', function (Blueprint $table) {
            $table->bigInteger(['correlative_api', 'response_api_status' ]);
        });
    }
}
