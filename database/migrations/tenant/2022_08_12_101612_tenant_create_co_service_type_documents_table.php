<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Helpers\RegularizeDataHelper;


class TenantCreateCoServiceTypeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_service_type_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('cufe_algorithm')->nullable();
            $table->string('prefix')->nullable();
            $table->timestamps();
        });

        RegularizeDataHelper::insertDataFromSeeder('co_service_type_documents');

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('co_service_type_documents');
    }

}
