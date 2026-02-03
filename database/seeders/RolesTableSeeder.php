<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$roles = [
    		['id' => 1, 'code' => 'admin', 'name' => 'Super Admin'],
            ['id' => 2, 'code' => 'hospital', 'name' => 'Hospital'],
            ['id' => 3, 'code' => 'doctor', 'name' => 'Psychiatrist'],
            ['id' => 4, 'code' => 'patient', 'name' => 'Psychiatric Patient / Client']
    	];

        DB::table('roles')->insert($roles);
    }
}
