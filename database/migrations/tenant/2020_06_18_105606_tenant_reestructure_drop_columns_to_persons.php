<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureDropColumnsToPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {

            $table->dropForeign(['province_id']);	
            $table->dropColumn(['province_id']);

            $table->dropForeign(['district_id']);	
            $table->dropColumn(['district_id']);

            $table->dropColumn(['condition', 'state']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            
            $table->char('province_id', 4)->nullable();
            $table->char('district_id', 6)->nullable();
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('district_id')->references('id')->on('districts');
            
            $table->string('state')->nullable()->after('address');
            $table->string('condition')->nullable()->after('address');
            
        });
    }
}
