<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantReestructureColumnsToDocumentItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        
        Schema::table('document_items', function (Blueprint $table) {

            $table->unsignedInteger('type_unit_id')->after('item');
            $table->foreign('type_unit_id')->references('id')->on('co_type_units');

            $table->unsignedInteger('tax_id')->nullable()->after('type_unit_id');
            $table->foreign('tax_id')->references('id')->on('co_taxes');
            
            $table->json('tax')->nullable()->after('tax_id');

            $table->decimal('total_tax', 10 ,2)->after('tax');
            $table->decimal('subtotal', 10 ,2)->after('total_tax');
            $table->decimal('discount', 10 ,2)->after('subtotal');
            

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::table('document_items', function (Blueprint $table) {

            $table->dropForeign(['type_unit_id']);	
            $table->dropColumn(['type_unit_id']);	

            $table->dropForeign(['tax_id']);	
            $table->dropColumn(['tax_id']);
            
            $table->dropColumn(['tax','total_tax','subtotal','discount']);

        }); 

    }
}
