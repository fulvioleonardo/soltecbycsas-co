<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantUpdateColumnsToDocumentsItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_items', function (Blueprint $table) {
            $table->decimal('total_tax', 18, 2)->change();
            $table->decimal('subtotal', 18, 2)->change();
            $table->decimal('discount', 18, 2)->change();
            $table->decimal('total', 18, 2)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_items', function (Blueprint $table) {
            $table->float('total_tax', 10, 2)->change();
            $table->float('subtotal', 10, 2)->change();
            $table->float('discount', 12, 2)->change();
            $table->float('total', 12, 2)->change();

        });
    }
}
