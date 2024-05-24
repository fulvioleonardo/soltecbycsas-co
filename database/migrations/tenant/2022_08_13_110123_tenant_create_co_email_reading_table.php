<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoEmailReadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_email_reading', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->string('email_user', 300)->index();
            
            $table->date('start_date')->index();
            $table->time('start_time')->index();

            $table->date('end_date')->nullable()->index();
            $table->time('end_time')->nullable()->index();

            $table->boolean('success')->default(false);
            $table->text('errors')->nullable();
            $table->string('imap_server', 300);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_email_reading');
    }
}
