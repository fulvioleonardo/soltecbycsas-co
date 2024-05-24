<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Models\Tenant\City;
use Modules\Factcolombia1\Models\Tenant\Department;

class UpdateCoCitiesSantaFeDeAntioquiaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        City::where('id', 12542)->update(['name' => 'Santa Fé De Antioquia']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        City::where('id', 12542)->update(['name' => 'Antioquia']);
    }
}
