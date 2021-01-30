<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class OccupationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'occupation']
        ];
        
        DB::table('master_types')->insert($master_types);

        $occupations = [
            ['master_type_slug' => 'occupation', 'name' =>'Plumber', 'slug' =>'Plumber', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Electrician', 'slug' =>'Electrician', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Architect', 'slug' =>'Architect', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Contractor', 'slug' =>'Contractor', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Lawyer', 'slug' =>'Lawyer', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Teacher', 'slug' =>'Teacher', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Interior designer', 'slug' =>'Interior designer', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Administrative assistant', 'slug' =>'Administrative assistant', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'HVAC engineer', 'slug' =>'HVAC engineer', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Sheriff', 'slug' =>'Sheriff', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Handyman', 'slug' =>'Handyman', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Painter', 'slug' =>'Painter', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Pilot-Commercial', 'slug' =>'Pilot-Commercial', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Attorney', 'slug' =>'Attorney', 'is_active' => 1],
            ['master_type_slug' => 'occupation', 'name' =>'Others', 'slug' =>'Others', 'is_active' => 1]
        ];

        DB::table('masters')->insert($occupations);
    }
}
