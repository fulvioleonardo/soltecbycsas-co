<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataModuleForInvoiceHealth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('modules')->insert([
            ['id'=> 20, 'value' => 'invoicehealth', 'description' => 'Factura sector salud']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('modules')->where('id', 20)->delete();
    }

}
