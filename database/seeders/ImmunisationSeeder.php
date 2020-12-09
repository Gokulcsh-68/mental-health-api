<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ImmunisationSeeder extends Seeder
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
                'master_type_slug' => 'immunisation',
                'slug' =>  'bcg-vaccine',
                'name' => 'BCG Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Birth']
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'hepatitis-b-vaccine',
                'name' => 'Hepatitis B Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' =>'Birth'],
                        ['treatment' => 'dose', 'periods' => '6w' ],
                        ['treatment' => 'dose', 'periods' => '6m' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'opv',
                'name' => 'OPV',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'drop','periods' =>'Birth'],
                        ['treatment' => 'drop','periods' =>'6m'],
                        ['treatment' => 'drop','periods' =>'9m'],
                        ['treatment' => 'drop','periods' =>'4y-6y']
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'ipv',
                'name' => 'IPV',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => '6w' ],
                        ['treatment' => 'dose', 'periods' => '10w' ],
                        ['treatment' => 'dose', 'periods' => '14w' ],
                        ['treatment' => 'dose', 'periods' => '16m-18m']
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'dtp-(dtwp-dtap)-vaccine',
                'name' => 'DTP (DTwP / DTaP) Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => '6w' ],
                        ['treatment' => 'dose', 'periods' => '6w'],
                        ['treatment' => 'dose', 'periods' => '10w'],
                        ['treatment' => 'dose', 'periods' => '16m-18m'],
                        ['treatment' => 'dose', 'periods' => '4y-6y']
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'hib-vaccine',
                'name' => 'Hib Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' =>'6w'],
                        ['treatment' => 'dose', 'periods' =>'10w'],
                        ['treatment' => 'dose', 'periods' =>'14w'],
                        ['treatment' => 'dose', 'periods' =>'16m-18m']
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'pneumococcal-vaccine',
                'name' => 'Pneumococcal Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => '6w' ],
                        ['treatment' => 'dose', 'periods' => '10w' ],
                        ['treatment' => 'dose', 'periods' => '14w' ],
                        ['treatment' => 'dose', 'periods' => '15m' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'rotavirus-vaccine',
                'name' => 'Rotavirus Vaccine',
                'attributes' =>json_encode([
                    "values" => [ 
                        ['treatment' => 'drop', 'periods' => '6w' ],
                        ['treatment' => 'drop', 'periods' => '10w' ],
                        ['treatment' => 'drop', 'periods' => '14w' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'influenza-virus-vaccine',
                'name' => 'Influenza Virus Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' =>'6m'],
                        ['treatment' => 'dose', 'periods' =>'7m' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'mmr-vaccine',
                'name' => 'MMR Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => '9m'],
                        ['treatment' => 'dose', 'periods' =>  '15m'],
                        ['treatment' => 'dose', 'periods' =>'4y-6y' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'typhoid-conjugate-vaccine', 
                'name' => 'Typhoid Conjugate Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' =>'10m-12m'],
                        ['treatment' => 'dose', 'periods' =>'2y' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'hepatitis-a-vaccine',
                'name' => 'Hepatitis A Vaccine',
                'attributes' =>json_encode([
                    "values" => [ ['treatment' => 'dose', 'periods' => '12m' ],
                    ['treatment' => 'dose', 'periods' =>   '18m' ]]]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'chickenpox(varicella)',
                'name' => 'Chickenpox (Varicella)',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' =>    '15m' ],
                        ['treatment' => 'dose', 'periods' =>   '4y-6y' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'tdap-vaccine',
                'name' => 'Tdap Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' =>   '10y-12y' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'hpv-vaccine',
                'name' => 'HPV Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => '10y-12y' ]
                    ]
                ]),
                'is_active' => 1
            ]
    	];

        DB::table('masters')->insert($health_types);
    }
}
