<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddQuotationIdToCoRemissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_remissions', function (Blueprint $table) {
            $table->unsignedInteger('quotation_id')->nullable()->after('currency_id');
            $table->foreign('quotation_id')->references('id')->on('quotations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_remissions', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            $table->dropColumn('quotation_id');   
        });
    }
}
