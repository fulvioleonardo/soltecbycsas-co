<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Models\Tenant\City;
use Modules\Factcolombia1\Models\Tenant\Department;

class UpdateCoCitiesTable extends Migration
{
    protected $createdAt = '2020-12-23 10:00:00';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $departmentsCount = Department::query()->count();
        if ($departmentsCount !== 0) {
            City::updateCities($this->createdAt);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        City::where('created_at', $this->createdAt)->forceDelete();
    }
}
