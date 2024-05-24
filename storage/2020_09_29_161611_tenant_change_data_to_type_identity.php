<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeDataToTypeIdentity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('co_type_identity_documents')) {

            DB::table('co_type_identity_documents')->where('id', 1)->update(['name' => 'Registro civil', 'code' => '11a']);
            DB::table('co_type_identity_documents')->where('id', 2)->update(['name' => 'Tarjeta de identidad', 'code' => '12a']);
            DB::table('co_type_identity_documents')->where('id', 3)->update(['name' => 'Cédula de ciudadanía', 'code' => '13a']);
            DB::table('co_type_identity_documents')->where('id', 4)->update(['name' => 'Tarjeta de extranjería', 'code' => '21a']);
            DB::table('co_type_identity_documents')->where('id', 5)->update(['name' => 'Cédula de extranjería', 'code' => '22a']);

            DB::table('co_type_identity_documents')->where('id', 6)->update(['name' => 'NIT', 'code' => '31a']);
            DB::table('co_type_identity_documents')->where('id', 7)->update(['name' => 'Pasaporte', 'code' => '41a']);
            DB::table('co_type_identity_documents')->where('id', 8)->update(['name' => 'Documento de identificación extranjero', 'code' => '42a']);
            DB::table('co_type_identity_documents')->where('id', 9)->update(['name' => 'NIT de otro país', 'code' => '50a']);
            DB::table('co_type_identity_documents')->where('id', 10)->update(['name' => 'NUIP *', 'code' => '91a']);



            DB::table('co_type_identity_documents')->where('id', 1)->update(['name' => 'Registro civil', 'code' => '11']);
            DB::table('co_type_identity_documents')->where('id', 2)->update(['name' => 'Tarjeta de identidad', 'code' => '12']);
            DB::table('co_type_identity_documents')->where('id', 3)->update(['name' => 'Cédula de ciudadanía', 'code' => '13']);
            DB::table('co_type_identity_documents')->where('id', 4)->update(['name' => 'Tarjeta de extranjería', 'code' => '21']);
            DB::table('co_type_identity_documents')->where('id', 5)->update(['name' => 'Cédula de extranjería', 'code' => '22']);

            DB::table('co_type_identity_documents')->where('id', 6)->update(['name' => 'NIT', 'code' => '31']);
            DB::table('co_type_identity_documents')->where('id', 7)->update(['name' => 'Pasaporte', 'code' => '41']);
            DB::table('co_type_identity_documents')->where('id', 8)->update(['name' => 'Documento de identificación extranjero', 'code' => '42']);
            DB::table('co_type_identity_documents')->where('id', 9)->update(['name' => 'NIT de otro país', 'code' => '50']);
            DB::table('co_type_identity_documents')->where('id', 10)->update(['name' => 'NUIP *', 'code' => '91']);

        }



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_type_identity_documents');
    }
}
