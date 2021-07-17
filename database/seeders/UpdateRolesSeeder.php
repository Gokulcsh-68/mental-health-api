<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$roles = [
            ['code' => 'scancentre', 'name' => 'Scan Centre']
        ];

        DB::table('roles')->insert($roles);
    }
}
