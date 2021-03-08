<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'chief-complaints'],
            [ 'slug' => 'icd'],
            [ 'slug' => 'notes'],
        ];
        
        DB::table('master_types')->insert($master_types);


        $health_types = [
            [
                'master_type_slug' => 'chief-complaints', 
                'slug' => 'chief-complaints', 
                'name' => 'Chief Complaints',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'icd', 
                'slug' => 'icd', 
                'name' => 'ICD 10',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'notes', 
                'slug' => 'notes', 
                'name' => 'Notes',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ]
        ];

        DB::table('masters')->insert($health_types);
    }
}
