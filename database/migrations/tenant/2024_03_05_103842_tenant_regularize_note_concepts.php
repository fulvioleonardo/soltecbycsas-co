<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Models\Tenant\NoteConcept;

class TenantRegularizeNoteConcepts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        NoteConcept::where('id', 4)->update(['id' => 4, 'type_document_id' => 3, 'name' => 'Devolución parcial de los bienes y/o no aceptación parcial del servicio', 'code' => '1']);
        NoteConcept::where('id', 5)->update(['id' => 5, 'type_document_id' => 3, 'name' => 'Anulación del documento soporte en adquisiciones efectuadas a sujetos no obligados a expedir factura de venta o documento equivalente', 'code' => '2']);
        NoteConcept::where('id', 6)->update(['id' => 6, 'type_document_id' => 3, 'name' => 'Rebaja  o descuento parcial o total', 'code' => '3']);
        NoteConcept::where('id', 7)->update(['id' => 7, 'type_document_id' => 3, 'name' => 'Ajuste de precio', 'code' => '4']);
        NoteConcept::where('id', 8)->update(['id' => 8, 'type_document_id' => 3, 'name' => 'Otros', 'code' => '5']);
        if(count(NoteConcept::where('id', 9)->get()) > 0)
            NoteConcept::find(9)->forceDelete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        NoteConcept::where('id', 4)->update(['id' => 4, 'type_document_id' => 3, 'name' => 'Devolución de parte de los bienes; no aceptación de partes del servicio', 'code' => '1']);
        NoteConcept::where('id', 5)->update(['id' => 5, 'type_document_id' => 3, 'name' => 'Anulación de factura electrónica', 'code' => '2']);
        NoteConcept::where('id', 6)->update(['id' => 6, 'type_document_id' => 3, 'name' => 'Rebaja total aplicada', 'code' => '3']);
        NoteConcept::where('id', 7)->update(['id' => 7, 'type_document_id' => 3, 'name' => 'Descuento total aplicado', 'code' => '4']);
        NoteConcept::where('id', 8)->update(['id' => 8, 'type_document_id' => 3, 'name' => 'Rescisión: nulidad por falta de requisitos', 'code' => '5']);
        NoteConcept::create(['type_document_id' => 3, 'name' => 'Otros', 'code' => '6']);
        NoteConcept::where('code', 6)->update(['id' => 9, 'type_document_id' => 3, 'name' => 'Otros', 'code' => '6']);
    }
}
