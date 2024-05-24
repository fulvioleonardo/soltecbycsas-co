<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('documents', function (Blueprint $table) {
            
            $table->unsignedInteger('state_document_id')->default(1)->after('id');
            $table->foreign('state_document_id')->references('id')->on('co_state_documents');

            // $table->dropForeign(['document_type_id']);
            $table->unsignedInteger('type_document_id')->nullable()->after('id');
            $table->foreign('type_document_id')->references('id')->on('co_type_documents');


            $table->string('prefix')->nullable()->after('soap_type_id');
            $table->string('number')->change();

            $table->unique(['prefix', 'number']);	

            $table->string('acknowledgment_received')->nullable()->after('number');
            $table->string('cufe')->nullable()->after('number');
            $table->string('xml')->nullable()->after('number');

            $table->unsignedInteger('type_invoice_id')->nullable()->after('acknowledgment_received');
            $table->foreign('type_invoice_id')->references('id')->on('co_type_invoices');

            $table->unsignedInteger('currency_id')->after('type_invoice_id');
            $table->foreign('currency_id')->references('id')->on('co_currencies');
 
            $table->date('date_expiration')->nullable()->after('currency_id');

            $table->string('observation')->nullable()->after('date_expiration');

            $table->unsignedInteger('reference_id')->nullable()->after('observation');
            $table->foreign('reference_id')->references('id')->on('co_documents');

            $table->unsignedInteger('note_concept_id')->nullable()->after('reference_id');
            $table->foreign('note_concept_id')->references('id')->on('co_note_concepts');

            $table->decimal('sale', 10 ,2)->after('note_concept_id');

            $table->json('taxes')->after('sale');

            $table->decimal('total_tax', 10 ,2)->after('taxes');

            $table->decimal('subtotal', 10 ,2)->after('total_tax');

            $table->unsignedInteger('version_ubl_id')->after('subtotal');
            $table->foreign('version_ubl_id')->references('id')->on('co_version_ubls');

            $table->unsignedInteger('ambient_id')->after('version_ubl_id');
            $table->foreign('ambient_id')->references('id')->on('co_ambients');

            $table->mediumText('response_api')->nullable()->after('ambient_id');

            
            $table->unsignedBigInteger('payment_form_id')->after('response_api');
            $table->foreign('payment_form_id')->references('id')->on('co_payment_forms');

            $table->unsignedBigInteger('payment_method_id')->after('payment_form_id');
            $table->foreign('payment_method_id')->references('id')->on('co_payment_methods');

            $table->tinyInteger('time_days_credit')->after('payment_method_id')->nullable();

            $table->bigInteger('correlative_api')->after('time_days_credit')->nullable();
            $table->mediumText('response_api_status')->after('correlative_api')->nullable();

        });


        Schema::table('documents', function (Blueprint $table) {
            
            // $table->unsignedInteger('document_type_id')->change();
            // $table->foreign('document_type_id')->references('id')->on('co_type_documents');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('documents', function (Blueprint $table) {
            
            $table->dropForeign(['state_document_id'])->default(1);	
            $table->dropColumn(['state_document_id']);

            // $table->dropForeign(['document_type_id']);
            $table->dropForeign(['type_document_id']);	
            $table->dropColumn(['type_document_id']);

            $table->dropColumn(['prefix']);
            $table->integer('number')->change();

            $table->dropUnique(['prefix', 'number']);	

            $table->dropColumn(['acknowledgment_received', 'cufe', 'xml']);

            $table->dropForeign(['type_invoice_id']);	
            $table->dropColumn(['type_invoice_id']);

            $table->dropForeign(['currency_id']);	
            $table->dropColumn(['currency_id']);

            $table->dropForeign(['reference_id']);	
            $table->dropColumn(['date_expiration', 'observation', 'reference_id']);


            $table->dropForeign(['note_concept_id']);	
            $table->dropColumn(['note_concept_id']);

            $table->dropColumn(['sale', 'taxes', 'total_tax', 'subtotal']);


            $table->dropForeign(['version_ubl_id']);	
            $table->dropColumn(['version_ubl_id']);

            $table->dropForeign(['ambient_id']);	
            $table->dropColumn(['ambient_id']);

            $table->dropColumn(['response_api']);
            
            $table->dropForeign(['payment_form_id']);	
            $table->dropColumn(['payment_form_id']);

            $table->dropForeign(['payment_method_id']);	
            $table->dropColumn(['payment_method_id']);

            $table->dropColumn(['time_days_credit', 'correlative_api', 'response_api_status']);

        });


        Schema::table('documents', function (Blueprint $table) {
            
            // $table->string('document_type_id')->change();
            // $table->foreign('document_type_id')->references('id')->on('cat_document_types');

        });
        


    }
}
