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
    		['master_type_slug' => 'speciality', 'slug' => 'allergy-and-immunology','name' => 'Allergy and Immunology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'anaesthesiology','name' => 'Anaesthesiology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'dermatology','name' => 'Dermatology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'diagnostic-radiology','name' => 'Diagnostic Radiology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'emergency-medicine','name' => 'Emergency Medicine', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'family-medicine','name' => 'Family Medicine', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'neurology','name' => 'Neurology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'gynecology','name' => 'Gynecology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'opthalmology','name' => 'Opthalmology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'pathology','name' => 'Pathology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'pediatrics','name' => 'Pediatrics', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'psychiatry','name' => 'Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'radiation-oncology','name' => 'Radiation Oncology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'surgery','name' => 'Surgery', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'urology','name' => 'Urology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'cardiology','name' => 'Cardiology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'geriatrics','name' => 'Geriatrics', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'endocrinology','name' => 'Endocrinology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'nephrology','name' => 'Nephrology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'oncology','name' => 'Oncology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'sexology','name' => 'Sexology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'hematology','name' => 'Hematology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'otolarynglogy','name' => 'Otolarynglogy', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'hepatology','name' => 'Hepatology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'palliative care','name' => 'Palliative care', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'proctology','name' => 'Proctology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'podiatry','name' => 'Podiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'pulmonolgy','name' => 'Pulmonolgy', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'gastroenterology','name' => 'Gastroenterology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'otorhinolaryngology','name' => 'Otorhinolaryngology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'andrology','name' => 'Andrology', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'rheumatology','name' => 'Rheumatology', 'is_active' => 1],
    	];

        DB::table('masters')->insert($speciality);
    }
}
