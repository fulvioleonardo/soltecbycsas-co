<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddPaymentToCoDocument extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_documents', function (Blueprint $table) {
          
            $table->unsignedInteger('payment_form_id')->default(1);
            $table->unsignedInteger('payment_method_id')->default(10);
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
              $table->dropColumn(['payment_form_id', 'payment_method_id']);
        });
    }
}
