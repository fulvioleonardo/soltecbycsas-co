<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantUpdateColumnToCoTypeDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_type_documents', function (Blueprint $table) {
            $table->dropUnique('co_type_documents_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_type_documents', function (Blueprint $table) {
            $table->unique('code');
        });
    }
}
