<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCoEmailReadingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('co_email_reading_details', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('co_email_reading_id');

            $table->string('email_user', 300);
            $table->string('email_id');

            $table->text('subject');
            $table->string('from_host');
            $table->string('from_name', 300);
            $table->string('from_address', 300);
            $table->string('sender_host', 300);

            $table->json('api_validation_response')->nullable();

            $table->unique(['email_user', 'email_id']);

            $table->foreign('co_email_reading_id')->references('id')->on('co_email_reading')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('co_email_reading_details');
    }
}
