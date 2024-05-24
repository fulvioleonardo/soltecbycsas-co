<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureDropColumnsToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            
            $table->dropForeign(['state_type_id']);	
            $table->dropColumn(['state_type_id']);	

            $table->dropColumn(['ubl_version']);
            
            $table->dropForeign(['group_id']);	
            $table->dropColumn(['group_id']);

            $table->dropForeign(['document_type_id']);	
            $table->dropColumn(['document_type_id']);

            $table->dropColumn(['series']);

            $table->dropForeign(['currency_type_id']);	
            $table->dropColumn(['currency_type_id']);

            $table->dropColumn(['purchase_order']);
            $table->dropColumn(['plate_number']);
            $table->dropColumn(['exchange_rate_sale']);

            $table->dropColumn(['total_prepayment']);
            $table->dropColumn(['total_charge']);
            $table->dropColumn(['total_exportation']);
            $table->dropColumn(['total_free']);
            $table->dropColumn(['total_taxed']);
            $table->dropColumn(['total_unaffected']);
            $table->dropColumn(['total_exonerated']);
            $table->dropColumn(['total_igv']);
            $table->dropColumn(['total_base_isc']);
            $table->dropColumn(['total_isc']);
            $table->dropColumn(['total_base_other_taxes']);
            $table->dropColumn(['total_other_taxes']);
            $table->dropColumn(['total_taxes']);
            $table->dropColumn(['total_value']);

            $table->dropColumn(['has_prepayment']);
            $table->dropColumn(['affectation_type_prepayment']);
            $table->dropColumn(['was_deducted_prepayment']);

            $table->dropColumn(['charges','discounts','prepayments','guides','related','perception','detraction','legends']);

            $table->dropColumn(['additional_information','filename','hash','qr','has_xml','has_pdf','has_cdr','soap_shipping_response']);
            
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
            
            $table->char('state_type_id', 2);
            $table->foreign('state_type_id')->references('id')->on('state_types');

            $table->string('ubl_version');

            $table->char('group_id', 2);
            $table->foreign('group_id')->references('id')->on('groups');
            
            $table->string('document_type_id');
            $table->foreign('document_type_id')->references('id')->on('cat_document_types');

            $table->char('series', 4);

            $table->string('currency_type_id');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');


            $table->string('purchase_order')->nullable();
            $table->string('plate_number')->nullable();
            $table->decimal('exchange_rate_sale', 13, 3);

            $table->decimal('total_prepayment', 12, 2)->default(0);
            $table->decimal('total_charge', 12, 2)->default(0);
            $table->decimal('total_exportation', 12, 2)->default(0);
            $table->decimal('total_free', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2)->default(0);
            $table->decimal('total_unaffected', 12, 2)->default(0);
            $table->decimal('total_exonerated', 12, 2)->default(0);
            $table->decimal('total_igv', 12, 2)->default(0);
            $table->decimal('total_base_isc', 12, 2)->default(0);
            $table->decimal('total_isc', 12, 2)->default(0);
            $table->decimal('total_base_other_taxes', 12, 2)->default(0);
            $table->decimal('total_other_taxes', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);

            $table->boolean('has_prepayment')->default(false)->after('total'); 
            $table->string('affectation_type_prepayment')->nullable()->after('has_prepayment');
            $table->boolean('was_deducted_prepayment')->default(false)->after('has_prepayment'); 

            $table->json('charges')->nullable();
            $table->json('discounts')->nullable();
            $table->json('prepayments')->nullable();
            $table->json('guides')->nullable();
            $table->json('related')->nullable();
            $table->json('perception')->nullable();
            $table->json('detraction')->nullable();
            $table->json('legends')->nullable();

            $table->text('additional_information')->nullable()->after('charges');
            $table->string('filename')->nullable();
            $table->string('hash')->nullable();
            $table->longText('qr')->nullable();
            $table->boolean('has_xml')->default(false);
            $table->boolean('has_pdf')->default(false);
            $table->boolean('has_cdr')->default(false);
            $table->json('soap_shipping_response')->nullable()->after('total_canceled');
            
        });
    }
}
