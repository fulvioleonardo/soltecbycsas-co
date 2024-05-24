<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddResponseMessageQueryZipkeyToCoDocumentPayrolls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->text('response_message_query_zipkey')->after('response_api')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_document_payrolls', function (Blueprint $table) {
            $table->dropColumn('response_message_query_zipkey');
        });
    }
}
