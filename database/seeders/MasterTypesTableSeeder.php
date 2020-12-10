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
            ['slug' => 'units'],
            ['slug' => 'types'],
            ['slug' => 'vitals'],
            ['slug' => 'speciality'],
            ['slug' => 'ros'],
            ['slug' => 'physical-examination'],
            ['slug' => 'stroke-scale'],
            ['slug' => 'assessment-group'],
            ['slug' => 'history'],
            ['slug' => 'health'],
            ['slug' => 'immunisation'],
            ['slug' => 'consult-menu'],
    	];
        
        DB::table('master_types')->insert($master_types);
    }
}
