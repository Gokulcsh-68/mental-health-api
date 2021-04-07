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
    		['id' => 2, 'code' => 'hospitalgroup', 'name' => 'Hospital Group'],
    		['id' => 3, 'code' => 'hospital', 'name' => 'Hospital'],
    		['id' => 4, 'code' => 'folio', 'name' => 'Folio User'],
    		['id' => 5, 'code' => 'provider', 'name' => 'Provider']
    	];

        DB::table('roles')->insert($roles);
    }
}
