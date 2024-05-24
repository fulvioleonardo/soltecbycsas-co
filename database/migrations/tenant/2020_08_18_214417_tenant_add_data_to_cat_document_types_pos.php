<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataToCatDocumentTypesPos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('cat_document_types')->insert([
            ['id' => '90', 'active' => 1, 'description' => 'POS'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_document_types')->whereIn('id',['90'])->delete();

    }
}
