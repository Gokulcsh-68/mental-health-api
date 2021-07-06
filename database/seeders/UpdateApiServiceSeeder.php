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
            ['username' => 'ApiServiceToken', 'token' => 'DUMUqJrNeyuaPDSZSsMf91qjgwc5K3N7', 'expiry_date' => '2091-03-20 12:55:16','active'=> 1]
        ];

        DB::table('api_accesses')->insert($entity);
    }
}
