<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTypeTaxToCoTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_taxes', function (Blueprint $table) {
            

            $table->unsignedInteger('type_tax_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_taxes', function (Blueprint $table) {
            $table->dropColumn('type_tax_id');
        });
    }
}
