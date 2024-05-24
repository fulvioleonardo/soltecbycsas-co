<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantConfigurationPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuration_pos', function (Blueprint $table) {

            $table->increments('id');
            $table->char('prefix', 5);
            $table->string('resolution_number')->nullable();
            $table->date('resolution_date')->nullable();

            $table->date('date_from')->nullable();
            $table->date('date_end')->nullable();

            $table->string('from')->nullable();
            $table->string('to')->nullable();

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
        Schema::dropIfExists('configuration_pos');
    }
}
