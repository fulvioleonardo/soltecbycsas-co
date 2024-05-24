<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Factcolombia1\Helpers\RegularizeDataHelper;
 
class TenantAddTypeToCoTypeOvertimeSurcharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('co_type_overtime_surcharges', function (Blueprint $table) {
            $table->string('type')->after('percentage')->nullable()->unique();
        });

        RegularizeDataHelper::insertDataFromSeeder('co_type_overtime_surcharges');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('co_type_overtime_surcharges', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
