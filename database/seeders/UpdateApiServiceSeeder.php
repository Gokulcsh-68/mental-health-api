<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateApiServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entity = [
            ['username' => 'ApiServiceToken', 'token' => '795c37b127543cd5436b7006c06bd51e', 'expiry_date' => '2091-03-20 12:55:16','active'=> 1]
        ];

        DB::table('api_accesses')->insert($entity);
    }
}
