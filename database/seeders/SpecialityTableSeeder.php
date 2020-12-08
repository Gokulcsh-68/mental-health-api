<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SpecialityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$speciality = [
    		['master_type_slug' => 'speciality', 'slug' => 'Allergy and Immunology','name' => 'Allergy and Immunology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Anaesthesiology','name' => 'Anaesthesiology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Dermatology','name' => 'Dermatology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Diagnostic Radiology','name' => 'Diagnostic Radiology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Emergency Medicine','name' => 'Emergency Medicine', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Family Medicine','name' => 'Family Medicine', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Neurology','name' => 'Neurology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Gynecology','name' => 'Gynecology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Opthalmology','name' => 'Opthalmology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Pathology','name' => 'Pathology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Pediatrics','name' => 'Pediatrics', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Psychiatry','name' => 'Psychiatry', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Radiation Oncology','name' => 'Radiation Oncology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Surgery','name' => 'Surgery', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Urology','name' => 'Urology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Cardiology','name' => 'Cardiology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Geriatrics','name' => 'Geriatrics', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Endocrinology','name' => 'Endocrinology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Nephrology','name' => 'Nephrology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Oncology','name' => 'Oncology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Sexology','name' => 'Sexology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Hematology','name' => 'Hematology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Otolarynglogy','name' => 'Otolarynglogy', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Hepatology','name' => 'Hepatology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Palliative care','name' => 'Palliative care', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Proctology','name' => 'Proctology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Podiatry','name' => 'Podiatry', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Pulmonolgy','name' => 'Pulmonolgy', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Gastroenterology','name' => 'Gastroenterology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Otorhinolaryngology','name' => 'Otorhinolaryngology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Andrology','name' => 'Andrology', 'attributes' => json_encode([]), 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'Rheumatology','name' => 'Rheumatology', 'attributes' => json_encode([]), 'is_active' => 1],
    	];

        DB::table('masters')->insert($speciality);
    }
}
