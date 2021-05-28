<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AddHealthFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $health_types = [
            [
                'master_type_slug' => 'health_form', 
                'slug' => 'health-insurance', 
                'name' => 'Health Insurance',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ]
        ];

        DB::table('masters')->insert($health_types);
    }
}
