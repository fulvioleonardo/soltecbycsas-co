<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        App\Models\System\User::updateOrCreate(
			[
				'name' => 'Admin Instrador',
			],
			[
				'email' => 'admin@gmail.com',
				'password' => bcrypt('123456'),
			]
		);

        DB::connection('system')->table('plan_documents')->updateOrInsert(['id' => 1,], ['description' => 'Facturas, boletas, notas de débito y crédito, resúmenes y anulaciones']);
        DB::connection('system')->table('plan_documents')->updateOrInsert(['id' => 2,], ['description' => 'Guias de remisión']);
        DB::connection('system')->table('plan_documents')->updateOrInsert(['id' => 3,], ['description' => 'Retenciones']);
        DB::connection('system')->table('plan_documents')->updateOrInsert(['id' => 4,], ['description' => 'Percepciones']);
	
        App\Models\System\Plan::updateOrCreate(
			[
				'name' => 'Ilimitado',
			],
			[
				'pricing' =>  99,
				'limit_users' => 0,
				'limit_documents' =>  0,
				'plan_documents' => [1,2,3,4],
				'locked' => true
			]
		);

        $this->call([DataServiceMasterSeeder::class]);
    }
}
