<?php

use Illuminate\Database\Seeder;
use Modules\Factcolombia1\Models\Tenant\City;

class UpdateCitiesTentantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = '2020-12-23 10:00:00';
        City::updateCities($createdAt);
    }
}
