<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $histories = [
    		[
                'master_type_slug' => 'history', 
                'slug' => 'family-history', 
                'name' => 'Family History',
                'attributes' => json_encode([
                    'disaese' => ['Cancer', 'Heart Disease', 'Diabetes', 'Stroke', 'High Blood Pressure', 'High Cholestrol', 'Liver Disease', 'Alcohol or Drug Abuse', 'Anxiety,Depression or Psychiatric Illness', 'Tuberculosis', 'Anesthesia', 'Genetic Disorder', 'Allergies', 'Sinus', 'Asthma', 'Eczema', 'Hay Fever', 'Hives', 'Migraine', 'Thyroid Disease', 'Emphysema', 'Cystic Fibrosis', 'Pneumococcal Vaccine', 'Alive'],
                    'relations' => ['Grandparents', 'Father', 'Mother', 'Brothers', 'Sisters', 'Daughters', 'Sons']
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'history', 
                'slug' => 'social-history', 
                'name' => 'Social History',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'history', 
                'slug' => 'medical-history', 
                'name' => 'Medical History',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'history', 
                'slug' => 'surgical-history', 
                'name' => 'Surgical History',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'history', 
                'slug' => 'gynecological-history', 
                'name' => 'Gynecological History',
                'is_active' => 1,
            ],
    	];

        DB::table('masters')->insert($histories);
    }
}
