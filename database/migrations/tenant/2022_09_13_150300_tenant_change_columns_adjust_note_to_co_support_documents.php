<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeColumnsAdjustNoteToCoSupportDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_support_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_form_id')->nullable()->change();
            $table->unsignedBigInteger('payment_method_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_support_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_form_id')->change();
            $table->unsignedBigInteger('payment_method_id')->change();
        });
    }
}
