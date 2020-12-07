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
    		['code' => 'Allergy and Immunology','name' => 'Allergy and Immunology', 'is_active' => 1],
            ['code' => 'Anaesthesiology','name' => 'Anaesthesiology', 'is_active' => 1],
            ['code' => 'Dermatology','name' => 'Dermatology', 'is_active' => 1],
            ['code' => 'Diagnostic Radiology','name' => 'Diagnostic Radiology', 'is_active' => 1],
            ['code' => 'Emergency Medicine','name' => 'Emergency Medicine', 'is_active' => 1],
            ['code' => 'Family Medicine','name' => 'Family Medicine', 'is_active' => 1],
            ['code' => 'Neurology','name' => 'Neurology', 'is_active' => 1],
            ['code' => 'Gynecology','name' => 'Gynecology', 'is_active' => 1],
            ['code' => 'Opthalmology','name' => 'Opthalmology', 'is_active' => 1],
            ['code' => 'Pathology','name' => 'Pathology', 'is_active' => 1],
            ['code' => 'Pediatrics','name' => 'Pediatrics', 'is_active' => 1],
            ['code' => 'Psychiatry','name' => 'Psychiatry', 'is_active' => 1],
            ['code' => 'Radiation Oncology','name' => 'Radiation Oncology', 'is_active' => 1],
            ['code' => 'Surgery','name' => 'Surgery', 'is_active' => 1],
            ['code' => 'Urology','name' => 'Urology', 'is_active' => 1],
            ['code' => 'Cardiology','name' => 'Cardiology', 'is_active' => 1],
            ['code' => 'Geriatrics','name' => 'Geriatrics', 'is_active' => 1],
            ['code' => 'Endocrinology','name' => 'Endocrinology', 'is_active' => 1],
            ['code' => 'Nephrology','name' => 'Nephrology', 'is_active' => 1],
            ['code' => 'Oncology','name' => 'Oncology', 'is_active' => 1],
            ['code' => 'Sexology','name' => 'Sexology', 'is_active' => 1],
            ['code' => 'Hematology','name' => 'Hematology', 'is_active' => 1],
            ['code' => 'Otolarynglogy','name' => 'Otolarynglogy', 'is_active' => 1],
            ['code' => 'Hepatology','name' => 'Hepatology', 'is_active' => 1],
            ['code' => 'Palliative care','name' => 'Palliative care', 'is_active' => 1],
            ['code' => 'Proctology','name' => 'Proctology', 'is_active' => 1],
            ['code' => 'Podiatry','name' => 'Podiatry', 'is_active' => 1],
            ['code' => 'Pulmonolgy','name' => 'Pulmonolgy', 'is_active' => 1],
            ['code' => 'Gastroenterology','name' => 'Gastroenterology', 'is_active' => 1],
            ['code' => 'Otorhinolaryngology','name' => 'Otorhinolaryngology', 'is_active' => 1],
            ['code' => 'Andrology','name' => 'Andrology', 'is_active' => 1],
            ['code' => 'Rheumatology','name' => 'Rheumatology', 'is_active' => 1],
    	];

        DB::table('speciality')->insert($speciality);
    }
}
