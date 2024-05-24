<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeNullableColumnsAdjustNotesToCoDocumentPayrolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->json('payment')->nullable()->change();
            $table->json('payment_dates')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->json('payment')->change();
            $table->json('payment_dates')->change();
        });
    }
}
