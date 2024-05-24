<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataToCoTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('co_taxes')) {

            DB::table('co_taxes')->insert([
                [ 'name' => 'IVA5', 'code' => '71', 'rate' => '5.0', 'conversion' => '100.0', 'created_at' => '2020-10-25 03:21:51', 'updated_at' => '2020-10-25 03:21:51', 'type_tax_id' => 1 ],
            ]);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('co_taxes')->where('name','IVA5')->delete();
    }
}
