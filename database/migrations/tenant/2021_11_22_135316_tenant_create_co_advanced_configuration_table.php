<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoAdvancedConfigurationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('co_advanced_configuration', function (Blueprint $table) {

            $table->bigIncrements('id'); 
            $table->decimal('minimum_salary', 18, 2);
            $table->decimal('transportation_allowance', 18, 2);
            $table->timestamps();
            
        });

        DB::table('co_advanced_configuration')->insert([
            ['minimum_salary' => 908526, 'transportation_allowance' => 106454, 'created_at' => now(), 'updated_at' => now()],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_advanced_configuration');
    }
}
