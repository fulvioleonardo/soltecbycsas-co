<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('co_companies', function(Blueprint $table) {
            $table->increments('id');
            $table->string('identification_number')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('subdomain', 10)->unique();
            $table->bigInteger('hostname_id')->unsigned()->nullable();
            $table->foreign('hostname_id')->references('id')->on('hostnames')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('co_companies');
    }
}
