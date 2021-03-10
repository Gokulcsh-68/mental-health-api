<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class HealthFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('masters')->Where('master_type_slug','health_form')->delete();

        DB::table('master_types')->Where('slug','health_form')->delete();

        $master_types = [
            [ 'slug' => 'health_form'],
        ];
        
        DB::table('master_types')->insert($master_types);


        $health_types = [
            [
                'master_type_slug' => 'health_form', 
                'slug' => 'chief-complaints', 
                'name' => 'Chief Complaints',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health_form', 
                'slug' => 'icd', 
                'name' => 'ICD 10',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health_form', 
                'slug' => 'notes', 
                'name' => 'Notes',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health_form', 
                'slug' => 'hpi', 
                'name' => 'HPI',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health_form', 
                'slug' => 'procedure', 
                'name' => 'Procedure',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ]
        ];

        DB::table('masters')->insert($health_types);
    }
}
