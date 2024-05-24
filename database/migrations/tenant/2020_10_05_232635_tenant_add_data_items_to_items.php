<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataItemsToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::table('co_taxes')->insert([
//            [ 'name' => 'IVA0', 'code' => '70', 'rate' => 0, 'conversion' => '100.00', 'is_percentage' => true, 'type_tax_id' => 1 ],
//        ]);
//        $tax0 = DB::table('co_taxes')->where('name', 'IVA0')->first();

//        $tax_iva = DB::table('co_taxes')->where('code', '01')->first();

//        DB::table('items')->insert([
//            [ 'name' => 'Administracion', 'internal_id' => 'aiu00001', 'item_type_id' => '01', 'tax_id' => $tax0->id, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
//            [ 'name' => 'Imprevisto', 'internal_id' => 'aiu00002', 'item_type_id' => '01', 'tax_id' => $tax0->id, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
//            [ 'name' => 'Utilidad', 'internal_id' => 'aiu00003', 'item_type_id' => '01', 'tax_id' => $tax_iva->id, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
//        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        DB::table('items')->where('internal_id','aiu00001')->delete();
//        DB::table('items')->where('internal_id','aiu00002')->delete();
//        DB::table('items')->where('internal_id','aiu00003')->delete();
//        DB::table('co_taxes')->where('name','IVA0')->delete();
    }
}
