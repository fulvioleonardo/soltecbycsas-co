<?php

namespace Modules\Factcolombia1\Traits\System;

use Illuminate\Support\Facades\DB;
use Modules\Factcolombia1\Models\SystemService\{
    Company as ServiceCompany
};
use Modules\Factcolombia1\Models\System\{
    Company
};
use Carbon\Carbon;
use App\Models\System\Module;
use App\Models\System\Plan;

trait CompanyTrait
{


    public function createSystemCompany($request, $hostname){

        $company = Company::create([
            'identification_number' => $request->identification_number,
            'name' => $request->name,
            'email' => $request->email,
            'subdomain' => $request->subdomain,
            'limit_documents' => $request->limit_documents,
            'hostname_id' => $hostname->id,
            'economic_activity_code' => $request->economic_activity_code,
            'ica_rate' => $request->ica_rate,
            'type_identity_document_id' => $request->type_document_identification_id,
        ]);

        $companyservice = ServiceCompany::create([
            'user_id' => 1, //por default
            'identification_number' => $request->identification_number,
            'dv' => $request->dv, //por default
            'language_id' =>79,
            'tax_id' => 1,
            'type_environment_id' =>  2,
            'type_operation_id' =>  10,
            'type_document_identification_id' => $request->type_document_identification_id,
            'country_id' => 46,
            'department_id' => $request->department_id,
            'type_currency_id' =>  35,
            'type_organization_id' => $request->type_organization_id,
            'type_regime_id' => $request->type_regime_id,
            'type_liability_id' => $request->type_liability_id,
            'municipality_id' => $request->municipality_id,
            'merchant_registration' => $request->merchant_registration,
            'address' => $request->address,
            'phone' => $request->phone,
            'api_token' => $request->api_token,
        ]);

        return $company;
    }

    public function createCompanyApiDian($request) {
        $base_url = config('tenant.service_fact');
        $number = $request->identification_number;
        $dv = $request->dv;
        $ch = curl_init("{$base_url}ubl2.1/config/{$number}/{$dv}");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $bodyContent = [
            'type_document_identification_id'=> $request->type_document_identification_id,
            'type_organization_id'=> $request->type_organization_id,
            'type_regime_id'=> $request->type_regime_id,
            'type_liability_id'=> $request->type_liability_id,
            'business_name'=> $request->name,
            'merchant_registration'=> $request->merchant_registration,
            'municipality_id'=> $request->municipality_id,
            'address'=> $request->address,
            'phone'=> $request->phone,
            'email'=> $request->email,
            'language_id'=> $request->language_id,
            'tax_id'=> $request->tax_id,
            'type_environment_id'=> $request->type_environment_id,
            'type_operation_id'=> $request->type_operation_id,
            'country_id'=> $request->country_id,
            'type_currency_id'=> $request->type_currency_id
        ];

        $data_companiee = json_encode($bodyContent);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,($data_companiee));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json',
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }


    public function runTenantSeeder($request, $response, $company){

        //lleno la data maestra
        \Artisan::call('db:seed', array('--class' => 'DataMasterTenantSeeder'));
        //lleno data mestra del servicio
        \Artisan::call('db:seed', array('--class' => 'DataServiceMasterTenantSeeder'));

        $user_id = DB::connection('tenant')
            ->table('users')
            ->insert([
                'name' => 'Administrador',
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'api_token' => str_random(60),
                'establishment_id' => 1,
                'type' => 'admin', //$request->type
                'locked' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);


        self::createAccessModules($request, $user_id);


        DB::connection('tenant')
            ->table('co_companies')
            ->insert([
                'identification_number' => $company->identification_number,
                'name' => $company->name,
                'email' => $company->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'subdomain' => $company->subdomain,
                'limit_documents' => $company->limit_documents,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'version_ubl_id' => 1,
                'ambient_id' => 1,
                'type_identity_document_id' => $request->type_document_identification_id,
                'type_regime_id' => $request->type_regime_id, // estos valores son por default
                'currency_id' => 170, //// estos valores son por default
                'economic_activity_code' => $request->economic_activity_code,
                'ica_rate' => $request->ica_rate
            ]);


        //aqui ingreso la data por q es igual a la data q guarda la api
        DB::connection('tenant')
            ->table('co_service_companies')
            ->insert([
                'response_data_api' => json_encode($response),
                'message' => $response->message,
                'password' => $response->password,
                'api_token' => $response->token,
                'user_id' => 1, //por default
                'identification_number' => $request->identification_number,
                'dv' => $request->dv, //por default
                'language_id' => 79,
                'tax_id' => 1,
                'type_environment_id' => 2,
                'type_operation_id' => 10,
                'type_document_identification_id' => $request->type_document_identification_id,
                'country_id' =>46,
                'department_id' => $request->department_id,
                'type_currency_id' => 35,
                'type_organization_id' => $request->type_organization_id,
                'type_regime_id' => $request->type_regime_id,
                'type_liability_id' => $request->type_liability_id,
                'municipality_id' => $request->municipality_id,
                'merchant_registration' => $request->merchant_registration,
                'address' => $request->address,
                'phone' => $request->phone,

            ]);

        self::updateTypeUnits();
        self::updateTaxes();

        DB::connection('tenant')->table('establishments')->where('id', 1)->update([
            'country_id' => 47,
            'department_id' => 779,
            'city_id' => 12688,
        ]);

        DB::connection('tenant')->table('persons')->insert([
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
        ]);

        DB::connection('tenant')->table('co_taxes')->insert([
            [
                'name' => 'EXCENTO',
                'code' => '07',
                'rate' => '0.00',
                'conversion' => '100.00',
                'type_tax_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],[
                'name' => 'IVA0AIU',
                'code' => '98',
                'rate' => '0.00',
                'conversion' => '100.00',
                'type_tax_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],[
                'name' => 'IVA19AIU',
                'code' => '99',
                'rate' => '19',
                'conversion' => '100.00',
                'type_tax_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);

        DB::connection('tenant')->table('co_cities')->insert([
            [ 'name' => 'San Andres', 'department_id' => '801', 'created_at' => '2020-10-28 03:21:51', 'updated_at' => '2020-10-28 03:21:51' ],
            [ 'name' => 'Providencia', 'department_id' => '801', 'created_at' => '2020-10-28 03:21:51', 'updated_at' => '2020-10-28 03:21:51' ],
        ]);

        DB::connection('tenant')->table('items')->insert([
            ['name' => 'Administracion', 'internal_id' => 'aiu00001', 'item_type_id' => '01', 'tax_id' => 9, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
            ['name' => 'Imprevisto', 'internal_id' => 'aiu00002', 'item_type_id' => '01', 'tax_id' => 9, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
            ['name' => 'Utilidad', 'internal_id' => 'aiu00003', 'item_type_id' => '01', 'tax_id' => 10, 'unit_type_id' => 1, 'currency_type_id' => 170, 'sale_unit_price' => 0, 'purchase_unit_price' => 0],
        ]);

        //lleno data a la tabla co_cities
        \Artisan::call('db:seed', array('--class' => 'UpdateCitiesTentantSeeder'));
    }


    public static function createAccessModules($request, $user_id){

        // if($request->input('type') == 'admin'){

            $array_modules = [];

            foreach ($request->modules as $module) {
                if($module['checked']){
                    $array_modules[] = ['module_id' => $module['id'], 'user_id' => $user_id];

                    if($module['id'] == 1){
                        DB::connection('tenant')->table('module_level_user')->insert([
                            ['module_level_id' => 1, 'user_id' => $user_id],
                            ['module_level_id' => 2, 'user_id' => $user_id],
                            // ['module_level_id' => 3, 'user_id' => $user_id],
                            // ['module_level_id' => 4, 'user_id' => $user_id],
                            ['module_level_id' => 5, 'user_id' => $user_id],
                            // ['module_level_id' => 6, 'user_id' => $user_id],
                            ['module_level_id' => 7, 'user_id' => $user_id],
                            ['module_level_id' => 8, 'user_id' => $user_id],
                            ['module_level_id' => 9, 'user_id' => $user_id],
                        ]);
                    }
                }
            }

            DB::connection('tenant')->table('module_user')->insert($array_modules);

        // }else{

        //     DB::connection('tenant')->table('module_user')->insert([
        //         ['module_id' => 1, 'user_id' => $user_id],
        //         ['module_id' => 3, 'user_id' => $user_id],
        //         ['module_id' => 5, 'user_id' => $user_id],
        //     ]);

        // }
    }


    public static function updateTypeUnits(){

        //actualixo los type_tax_id en taxes tenant
        DB::connection('tenant')
            ->table('co_taxes')
            ->where('id', 1)
            ->update([
                'type_tax_id' => 1
            ]);

        DB::connection('tenant')
            ->table('co_taxes')
            ->where('id', 2)
            ->update([
                'type_tax_id' => 2
            ]);

        DB::connection('tenant')
            ->table('co_taxes')
            ->where('id', 3)
            ->update([
                'type_tax_id' => 7
            ]);

        DB::connection('tenant')
            ->table('co_taxes')
            ->where('id', 4)
            ->update([
                'type_tax_id' => 4
            ]);

        DB::connection('tenant')
            ->table('co_taxes')
            ->where('id', 5)
            ->update([
                'type_tax_id' => 6
            ]);

        DB::connection('tenant')
            ->table('co_taxes')
            ->where('id', 6)
            ->update([
                'type_tax_id' => 5
            ]);

        DB::table('co_taxes')->insert([
            [ 'name' => 'IVA5', 'code' => '71', 'rate' => '5.0', 'conversion' => '100.0', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'type_tax_id' => 1 ],
        ]);

    }

    public static function updateTaxes(){

        //ACTULIZO TYPE UNITS CON EL CODE DE LA API
        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 1)
            ->update([
                'code' => 70
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 2)
            ->update([
                'code' => 359
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 3)
            ->update([
                'code' => 70
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 4)
            ->update([
                'code' => 44
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 5)
            ->update([
                'code' => 640
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 6)
            ->update([
                'code' => 821
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 7)
            ->update([
                'code' => 813
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 8)
            ->update([
                'code' => 808
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 9)
            ->update([
                'code' => 770
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 10)
            ->update([
                'code' => 70
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 11)
            ->update([
                'code' => 825
            ]);

        DB::connection('tenant')
            ->table('co_type_units')
            ->where('id', 12)
            ->update([
                'code' => 730
            ]);

    }

    public function runTenantPeruSeeder($request){

        DB::connection('tenant')->table('companies')->insert([
            'identity_document_type_id' => '6',
            'number' => $request->input('identification_number'),
            'name' => $request->input('name'),
            'trade_name' => $request->input('name'),
            'soap_type_id' => '01',
            'soap_send_id' => '01',
            'soap_username' => null,
            'soap_password' => null,
            'soap_url' => null,
            'certificate' => null,
        ]);

        $plan = Plan::findOrFail(1);

        DB::connection('tenant')->table('configurations')->insert([
            'send_auto' => true,
            'locked_emission' =>  false,
            'locked_tenant' =>  false,
            'locked_users' =>  false,
            'limit_documents' =>  $plan->limit_documents,
            'limit_users' =>  $plan->limit_users,
            'plan' => json_encode($plan),
            'date_time_start' =>  date('Y-m-d H:i:s'),
            'quantity_documents' =>  0,
            'config_system_env' => 1
        ]);


        $establishment_id = DB::connection('tenant')->table('establishments')->insertGetId([
            'description' => 'Oficina Principal',
            'country_id' => null,
            'department_id' => null,
            'city_id' => null,
            'address' => '-',
            'email' => $request->input('email'),
            'telephone' => '-',
            'code' => '0000'
        ]);


        DB::connection('tenant')->table('series')->insert([
            ['establishment_id' => 1, 'document_type_id' => '80', 'number' => 'NV01'],
        ]);

    }


}
