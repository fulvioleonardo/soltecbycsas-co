<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoTypeOvertimeSurchargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_type_overtime_surcharges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->char('code');
            $table->float('percentage', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_type_overtime_surcharges');
    }
}
