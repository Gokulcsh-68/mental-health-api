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
            ['master_type_slug' => 'speciality', 'slug' => 'psychiatry', 'name' => 'Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'child-and-adolescent-psychiatry', 'name' => 'Child and Adolescent Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'geriatric-psychiatry', 'name' => 'Geriatric Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'addiction-psychiatry', 'name' => 'Addiction Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'forensic-psychiatry', 'name' => 'Forensic Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'consultation-liaison-psychiatry', 'name' => 'Consultation-Liaison Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'community-psychiatry', 'name' => 'Community Psychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'psychotherapy', 'name' => 'Psychotherapy', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'neuropsychiatry', 'name' => 'Neuropsychiatry', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'sleep-medicine-psychiatry', 'name' => 'Sleep Medicine (Psychiatry)', 'is_active' => 1],
            ['master_type_slug' => 'speciality', 'slug' => 'child-development-psychiatry', 'name' => 'Child Development Psychiatry', 'is_active' => 1],
             ['master_type_slug' => 'speciality', 'slug' => 'opthalmology', 'name' => 'opthalmology', 'is_active' => 1],

        ];

        DB::table('masters')->insertOrIgnore($speciality);
    }
}
