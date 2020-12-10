<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class dynamicFormsMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $is_active  = 1;

    	$ros = [
            ['master_type_slug' => 'ros', 'slug' => 'constitutional', 'name' => 'Constitutional', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'head', 'name' => 'Head', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'eye', 'name' => 'Eye', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'ears', 'name' => 'Ears', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'nose', 'name' => 'Nose', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'mouth', 'name' => 'Mouth', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'throat', 'name' => 'Throat', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'respiratory', 'name' => 'Respiratory', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'cardiovascular', 'name' => 'Cardiovascular', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'chest', 'name' => 'Chest', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'gastrointestinal', 'name' => 'Gastrointestinal', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'genitourinary-male', 'name' => 'Genitourinary - Male', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'genitourinary-female', 'name' => 'Genitourinary - Female', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'musculoskeletal', 'name' => 'Musculoskeletal', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'neurological', 'name' => 'Neurological', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'skin', 'name' => 'Skin', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'hair', 'name' => 'Hair', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'breast', 'name' => 'Breast', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'psychiatric', 'name' => 'Psychiatric', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'endocrinologic', 'name' => 'Endocrinologic', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'hematologic', 'name' => 'Hematologic', 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'allergic_immunologic', 'name' => 'Allergic / Immunologic', 'is_active' => $is_active],

    	];

        DB::table('masters')->insert($ros);

        $physical_examination = [['master_type_slug' => 'physical-examination', 'slug' => 'neck', 'name' => 'Neck', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'heart', 'name' => 'Heart', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'general-appearance', 'name' => 'General Appearance', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'neurological', 'name' => 'Neurological', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'heent', 'name' => 'HEENT', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'back-spine', 'name' => 'Back spine', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'extremities', 'name' => 'Extremities', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'chest', 'name' => 'Chest', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'abdomen', 'name' => 'Abdomen', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'skin', 'name' => 'Skin', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'nodes', 'name' => 'Nodes', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'breasts', 'name' => 'Breasts', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'genitalia', 'name' => 'Genitalia', 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'rectal', 'name' => 'Rectal', 'is_active' => $is_active],
        ];

        DB::table('masters')->insert($physical_examination);

         $stroke_scale = [
            ['master_type_slug' => 'stroke-scale', 'slug' => 'stroke-scale', 'name' => 'Stroke Scale', 'is_active' => $is_active],
        ];

        DB::table('masters')->insert($stroke_scale);


    }
}
