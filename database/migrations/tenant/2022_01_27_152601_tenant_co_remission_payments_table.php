<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCoRemissionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_remission_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('remission_id');
            $table->date('date_of_payment');
            $table->char('payment_method_type_id', 2);
            $table->string('reference')->nullable();
            $table->decimal('change',12, 2)->nullable();
            $table->decimal('payment', 12, 2);
            $table->foreign('remission_id')->references('id')->on('co_remissions')->onDelete('cascade');
            $table->foreign('payment_method_type_id')->references('id')->on('payment_method_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_remission_payments');
    }

}
