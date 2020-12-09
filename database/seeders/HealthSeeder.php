<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class HealthSeeder extends Seeder
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
                'master_type_slug' => 'health', 
                'slug' => 'allergy', 
                'name' => 'ALLERGY',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'health', 
                'slug' => 'medicine', 
                'name' => 'MEDICINE',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health', 
                'slug' => 'lab', 
                'name' => 'LAB',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health', 
                'slug' => 'scan', 
                'name' => 'SCAN',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health', 
                'slug' => 'diet', 
                'name' => 'DIET',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health', 
                'slug' => 'document', 
                'name' => 'DOCUMENT',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'health', 
                'slug' => 'wellness', 
                'name' => 'WELLNESS',
                'attributes' =>json_encode(
                    ["values" => ['Activity','Food','Fluid','Mood', 'Sleep']]
                ),

                'is_active' => 1,
            ],
    	];

        DB::table('masters')->insert($health_types);
    }
}
