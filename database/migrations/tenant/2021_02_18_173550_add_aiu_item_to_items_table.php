<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddAiuItemToItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$typeUnits = DB::table('co_type_units')->count();
		if ($typeUnits > 0) {
			DB::table('co_taxes')->insert([
				['name' => 'IVA0AIU', 'code' => '98', 'rate' => 0, 'conversion' => '100.00', 'is_percentage' => true, 'type_tax_id' => 1],
				['name' => 'IVA19AIU', 'code' => '99', 'rate' => 19, 'conversion' => '100.00', 'is_percentage' => true, 'type_tax_id' => 1],
			]);
			$tax0 = DB::table('co_taxes')->where('name', 'IVA0AIU')->first();

			$tax_iva = DB::table('co_taxes')->where('name', 'IVA19AIU')->first();

			DB::table('items')->insert([
				['name' => 'Administracion', 'internal_id' => 'aiu00001', 'item_type_id' => '01', 'tax_id' => $tax0->id, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
				['name' => 'Imprevisto', 'internal_id' => 'aiu00002', 'item_type_id' => '01', 'tax_id' => $tax0->id, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
				['name' => 'Utilidad', 'internal_id' => 'aiu00003', 'item_type_id' => '01', 'tax_id' => $tax_iva->id, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
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
		DB::table('items')->where('internal_id', 'aiu00001')->delete();
		DB::table('items')->where('internal_id', 'aiu00002')->delete();
		DB::table('items')->where('internal_id', 'aiu00003')->delete();
		DB::table('co_taxes')->where('name', 'IVA0AIU')->delete();
		DB::table('co_taxes')->where('name', 'IVA19AIU')->delete();
	}
}
