<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateImmunisationSeeder extends Seeder
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
                'name' => 'Bacillus Calmette–Guérin Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Birth']
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'opv',
                'name' => 'Oral poliovirus vaccines',
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
                'name' => 'Inactivated polio vaccine',
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
                'name' => 'Diphtheria Pertussis Tetanus (DTwP / DTaP) Vaccine',
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
                'name' => 'Haemophilus influenzae type b Vaccine',
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
                'slug' =>  'mmr-vaccine',
                'name' => 'Measles, Mumps, Rubella Vaccine',
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
                'slug' =>  'tdap-vaccine',
                'name' => 'Tdap (combined tetanus, diphtheria and acellular pertussis) Vaccine',
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
                'name' => 'Human papillomavirus Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => '10y-12y' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-rna-pfizer',
                'name' => 'Covid RNA Pfizer Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-rna-moderna',
                'name' => 'Covid RNA Moderna Vaccine',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-viral-vector-johnson',
                'name' => 'Covid Viral Vector Johnson & Johnson (Janssen)',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (single)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-protein-based-novavax',
                'name' => 'Covid protein based Novavax',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-viral-vector-oxfordaz',
                'name' => 'Covid Viral Vector Oxford-AstraZeneca',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-viral-vector-sputnikv',
                'name' => 'Covid Viral Vector Sputnik V',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-inactivated-virus-covaxin',
                'name' => 'Covid Inactivated Virus Covaxin',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-inactivated-virus-coronovac',
                'name' => 'Covid Inactivated Virus CoronoVac (Sinovac)',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'immunisation',
                'slug' =>  'covid-inactivated-virus-sinopharm',
                'name' => 'Covid Inactivated Virus Sinopharm',
                'attributes' =>json_encode([
                    "values" => [
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 1)' ],
                        ['treatment' => 'dose', 'periods' => 'Any Age (dose 2)' ]
                    ]
                ]),
                'is_active' => 1
            ]
    	];

        foreach ($health_types as $key => $value) {
            $chk = DB::table('masters')
                ->where('slug',$value['slug'])
                ->where('master_type_slug','immunisation')
                ->count();

                if($chk > 0){
                    DB::table('masters')
                        ->where('slug',$value['slug'])
                        ->where('master_type_slug','immunisation')
                        ->update($value);
                }
                else{
                    DB::table('masters')->insert($value);

                }
        }
    }
}
