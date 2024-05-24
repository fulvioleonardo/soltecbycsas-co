<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataToCities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*if (Schema::hasTable('co_cities')) {

            DB::table('co_cities')->insert([
                [ 'name' => 'San Andres', 'department_id' => '801', 'created_at' => '2020-10-28 03:21:51', 'updated_at' => '2020-10-28 03:21:51' ],
                [ 'name' => 'Providencia', 'department_id' => '801', 'created_at' => '2020-10-28 03:21:51', 'updated_at' => '2020-10-28 03:21:51' ],
            ]);

        }*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


    }
}
