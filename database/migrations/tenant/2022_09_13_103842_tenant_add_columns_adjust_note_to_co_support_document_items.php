<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsAdjustNoteToCoSupportDocumentItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_support_document_items', function (Blueprint $table) {
            $table->unsignedInteger('type_generation_transmition_id')->nullable()->change();
            $table->date('start_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_support_document_items', function (Blueprint $table) {
            $table->unsignedInteger('type_generation_transmition_id')->change();
            $table->date('start_date')->change();
        });
    }
}
