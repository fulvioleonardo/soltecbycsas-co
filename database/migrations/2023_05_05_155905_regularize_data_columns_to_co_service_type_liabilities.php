<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Models\SystemService\TypeLiability;


class RegularizeDataColumnsToCoServiceTypeLiabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ids_not_delete = [7, 9, 14, 112, 117];

        // eliminar datos
        TypeLiability::whereNotIn('id', $ids_not_delete)->delete();


        // crear / actualizar
        TypeLiability::where('id', 14)->update(['name' => 'Agente de retención IVA']);

        TypeLiability::updateOrCreate(
            [
                'id' => 112,
            ], 
            [
                'name' => 'Régimen simple de tributación',
                'code' => 'O-47',
            ]
        );
        
        TypeLiability::updateOrCreate(
            [
                'id' => 117,
            ], 
            [
                'name' => 'No aplica – Otros',
                'code' => 'R-99-PN',
            ]
        );

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
