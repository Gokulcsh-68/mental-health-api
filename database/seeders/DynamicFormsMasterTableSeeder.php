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
            ['master_type_slug' => 'ros', 'slug' => 'constitutional', 'name' => 'Constitutional','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'head', 'name' => 'Head','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'eye', 'name' => 'Eye','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'ears', 'name' => 'Ears','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'nose', 'name' => 'Nose','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'mouth', 'name' => 'Mouth','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'throat', 'name' => 'Throat','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'respiratory', 'name' => 'Respiratory','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'cardiovascular', 'name' => 'Cardiovascular','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'chest', 'name' => 'chest','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'gastrointestinal', 'name' => 'Gastrointestinal','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'genitourinary-male', 'name' => 'Genitourinary - Male','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'genitourinary-female', 'name' => 'Genitourinary - Female','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'musculoskeletal', 'name' => 'Musculoskeletal','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'neurological', 'name' => 'Neurological','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'skin', 'name' => 'Skin','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'hair', 'name' => 'Hair','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'breast', 'name' => 'Breast','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'psychiatric', 'name' => 'Psychiatric','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'endocrinologic', 'name' => 'Endocrinologic','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'hematologic', 'name' => 'Hematologic','attributes' => json_encode([]), 'is_active' => $is_active],
            ['master_type_slug' => 'ros', 'slug' => 'allergic_immunologic', 'name' => 'Allergic / Immunologic','attributes' => json_encode([]), 'is_active' => $is_active],

    	];

        DB::table('masters')->insert($ros);

        $physical_examination = [['master_type_slug' => 'physical-examination', 'slug' => 'neck', 'name' => 'Neck','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'heart', 'name' => 'Heart','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'general-appearance', 'name' => 'General Appearance','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'neurological', 'name' => 'Neurological','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'heent', 'name' => 'HEENT','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'back-spine', 'name' => 'Back spine','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'extremities', 'name' => 'Extremities','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'chest', 'name' => 'Chest','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'abdomen', 'name' => 'Abdomen','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'skin', 'name' => 'Skin','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'nodes', 'name' => 'Nodes','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'breasts', 'name' => 'Breasts','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'genitalia', 'name' => 'Genitalia','attributes' => json_encode([]), 'is_active' => $is_active],
        ['master_type_slug' => 'physical-examination', 'slug' => 'rectal', 'name' => 'Rectal','attributes' => json_encode([]), 'is_active' => $is_active],
        ];

        DB::table('masters')->insert($physical_examination);

         $stroke_scale = [
            ['master_type_slug' => 'stroke-scale', 'slug' => 'stroke-scale', 'name' => 'Stroke Scale','attributes' => json_encode([]), 'is_active' => $is_active],
        ];

        DB::table('masters')->insert($stroke_scale);


    }
}
