<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Helpers\RegularizeDataHelper;


class TenantCreateCoTypeGenerationTransmitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_type_generation_transmitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('code');
            $table->timestamps();
        });

        // csv
        RegularizeDataHelper::insertDataFromSeeder('co_type_generation_transmitions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_type_generation_transmitions');
    }
}
