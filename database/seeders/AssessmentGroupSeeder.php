<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AssessmentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assessmentGroups = [
            // Infants & Toddlers (0–5 years)
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-mood-behavior',
                'name' => 'Mood & Behavior Changes',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role' => 'patient',
                    'type' => 'self-report',
                    'gender' => 'all',
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-sleep-feeding',
                'name' => 'Sleep & Feeding Patterns',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role' => 'patient',
                    'type' => 'self-report',
                    'gender' => 'all',
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-social-bonding',
                'name' => 'Social Interaction & Bonding',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role' => 'patient',
                    'type' => 'self-report',
                    'gender' => 'all',
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'infants-toddlers-development',
                'name' => 'Developmental Milestones',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role' => 'patient',
                    'type' => 'self-report',
                    'gender' => 'male',
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'male-infants-physical-growth',
                'name' => 'Physical Growth Patterns (Male)',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role' => 'patient',
                    'type' => 'self-report',
                    'gender' => 'male',
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'assessment-group',
                'slug' => 'female-infants-physical-growth',
                'name' => 'Physical Growth Patterns (Female)',
                'attributes' => json_encode([
                    'age_group' => '0-5',
                    'role' => 'patient',
                    'type' => 'self-report',
                    'gender' => 'female',
                ]),
                'is_active' => 1,
            ],
        ];

        foreach ($assessmentGroups as $group) {
            DB::table('masters')->updateOrInsert(
                ['slug' => $group['slug']], // condition to check existing record
                $group
            );
        }
    }
}
