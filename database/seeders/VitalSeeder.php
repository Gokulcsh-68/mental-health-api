<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class VitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeders = [
    		[
                'master_type_slug' => 'units', 
                'slug' => 'Unit', 
                'name' => 'Unit',
                'attributes' => json_encode([
                    'Weight' => [
                        ["name" => "Feet", "code" => "Feet"],
                        ["name" => "Cm", "code"=> "Cm"]
                    ],

                    'Height' => [
                        ["name"=> "Lbs", "code"=> "Lbs"],
                        ["name"=> "Kg", "code"=> "Kg"]
                    ],

                    'Temperature' => [
                        ["name"=> "Fahrenheit", "code"=> "°F"],
                        ["name"=> "Celsius", "code"=> "°C"]
                    ],

                    'Blood' => [
                        ["name"=> "mg/dL", "code"=> "mg/dL"],
                        ["name"=> "mmol/L", "code"=> "mmol/L"]
                    ],

                    'Spo2' => [
                        ["name"=> "%", "code"=> "%"]
                    ],

                    'Leukocytes' => [
                        ["name"=> "-", "code"=> "Minus"],
                        ["name"=> "+", "code"=> "Plus"],
                        ["name"=> "++", "code"=> "Double Plus"],
                        ["name"=> "+++", "code"=> "Triple Plus"]
                    ],

                    'Protein' => [
                        ["name"=> "-", "code"=> "Minus"],
                        ["name"=> "+", "code"=> "Plus"],
                        ["name"=> "++", "code"=> "Double Plus"],
                        ["name"=> "+++", "code"=> "Triple Plus"],
                        ["name"=> "++++", "code"=> "Four Plus"],
                    ],

                    'RBC' => [
                        ["name"=> "-", "code"=> "Minus"],
                        ["name"=> "+", "code"=> "Plus"],
                        ["name"=> "++", "code"=> "Double Plus"],
                        ["name"=> "+++", "code"=> "Triple Plus"],
                        ["name"=> "++++", "code"=> "Four Plus"]
                    ],

                    'HearRate' => [
                        ["name"=> "Bpm", "code"=> "Bpm"]
                    ]
                ]),
                'is_active' => 1,
            ],

            [
                'master_type_slug' => 'types', 
                'slug' => 'type', 
                'name' => 'type',
                'attributes' => json_encode([
                    'Temperature' => [
                        ["name"=> "Oral", "code"=> "Oral"],
                        ["name"=> "Auxilury", "code"=> "Auxilury"],
                        ["name"=> "Rectal", "code"=> "Rectal"],
                        ["name"=> "Ear", "code"=> "Ear"],
                        ["name"=> "Skin/Infrared", "code"=> "Skin/Infrared"]
                    ],
                    'Blood' => [
                        ["name"=> "Fasting", "code"=> "Fasting"],
                        ["name"=> "Random", "code"=> "Random"],
                        ["name"=> "Post Prandial", "code"=> "Post Prandial"]
                    ],
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'BMI', 
                'name' => 'BMI',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'Temperature', 
                'name' => 'Temperature',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'BloodSugar', 
                'name' => 'Blood Sugar',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'SpO2', 
                'name' => 'SpO2',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'Urine', 
                'name' => 'Urine',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'BloodPressure', 
                'name' => 'Blood Pressure',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'HeartRate', 
                'name' => 'Heart Rate',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'vitals', 
                'slug' => 'LipidProfile', 
                'name' => 'Lipid Profile',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ]
    	];

        DB::table('masters')->insert($seeders);
    }
}
