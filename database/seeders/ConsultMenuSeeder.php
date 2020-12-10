<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ConsultMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consultMenus = [
    		[
                'master_type_slug' => 'consult-menu',
                'slug' =>  'demography',
                'name' => 'DEMOGRAPHY',
                'attributes' =>json_encode([
                    "values" => [
                        ['slug' => 'profile', 'label' => 'PROFILE'],
                        ['slug' => 'activity', 'label' => 'ACTIVITY'],
                        ['slug' => 'profile', 'label' => 'DIET'],
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'consult-menu',
                'slug' =>  'history-others',
                'name' => 'HISTORY OTHERS',
                'attributes' =>json_encode([
                    "values" => [
                        ['slug' => 'symptoms', 'label' => 'SYMPTOMS'],
                        ['slug' => 'vitals', 'label' => 'VITALS'],
                        ['slug' => 'hpi', 'label' => 'HPI'],
                        ['slug' => 'allergy', 'label' => 'ALLERGY'],
                        ['slug' => 'medicine', 'label' => 'MEDICINE'],
                        ['slug' => 'vaccine', 'label' => 'VACCINE'],
                        ['slug' => 'image-lab', 'label' => 'IMAGE / LAB'],
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'consult-menu',
                'slug' =>  'history',
                'name' => 'HISTORY',
                'attributes' =>json_encode([
                    "values" => [
                        ['slug' => 'medical', 'label' => 'MEDICAL'],
                        ['slug' => 'surgical', 'label' => 'SURGICAL'],
                        ['slug' => 'social', 'label' => 'SOCIAL'],
                        ['slug' => 'family', 'label' => 'FAMILY'],
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'consult-menu',
                'slug' =>  'examination',
                'name' => 'EXAMINATION',
                'attributes' =>json_encode([
                    "values" => [
                        ['slug' => 'review-of-system', 'label' => 'REVIEW OF SYSTEM'],
                        ['slug' => 'physical-exam', 'label' => 'PHYSICAL EXAM'],
                        ['slug' => 'stroke-scale', 'label' => 'STROKE SCALE'],
                    ]
                ]),
                'is_active' => 1
            ],
            [
                'master_type_slug' => 'consult-menu',
                'slug' =>  'assessment-and-plan',
                'name' => 'ASSESSMENT & PLAN',
                'attributes' =>json_encode([
                    "values" => [
                        ['slug' => 'medicine', 'label' => 'MEDICINE'],
                        ['slug' => 'lab', 'label' => 'LAB'],
                        ['slug' => 'imaging', 'label' => 'IMAGING'],
                        ['slug' => 'icd10', 'label' => 'ICD10'],
                        ['slug' => 'files', 'label' => 'FILES'],
                        ['slug' => 'notes', 'label' => 'NOTES'],
                    ]
                ]),
                'is_active' => 1
            ],
    	];

        DB::table('masters')->insert($consultMenus);
    }
}
