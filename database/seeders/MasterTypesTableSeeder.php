<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MasterTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$master_types = [
    		['slug' => 'gender'],
    		['slug' => 'country'],
    	];
        DB::table('master_types')->insert($master_types);
    }
}
