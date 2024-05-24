<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataToCoTaxesExcento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::table('co_taxes')->insert([
            [
                'name' => 'EXCENTO',
                'code' => '07',
                'rate' => '0.00',
                'conversion' => '100.00',
                'type_tax_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // DB::table('co_taxes')->where('code','07')->delete();
    }
}
