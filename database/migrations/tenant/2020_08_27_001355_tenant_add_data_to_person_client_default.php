<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDataToPersonClientDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*DB::table('persons')->insert([
            [   'type' => 'customers',
                'dv' => 7,
                'code' => '222222222222',
                'type_regime_id' => 2,
                'type_person_id' => 2,
                'identity_document_type_id' => 3,
                'number' => '222222222222',
                'name' => 'Cliente POS',
                'country_id' => 47,
                'department_id' => 779,
                'city_id' => 12688,
                'address' => 'direccion POS',
                'email' => 'clientepos@gmail.com',
                'telephone' => '999993333',
                'contact_phone' => '999993333',
                'contact_name' => 'contact pos',
                'percentage_perception' => '0.0',
                'enabled' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::table('persons')->where('number','222222222222')->delete();
    }
}
