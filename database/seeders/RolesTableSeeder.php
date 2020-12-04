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
    		['id' => 2, 'code' => 'school', 'name' => 'School Admin'],
    		['id' => 3, 'code' => 'staff', 'name' => 'Staff'],
    		['id' => 4, 'code' => 'student', 'name' => 'Student'],
    		['id' => 5, 'code' => 'provider', 'name' => 'Provider'],
    	];

        DB::table('roles')->insert($roles);
    }
}
