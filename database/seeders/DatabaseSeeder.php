<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            MasterTypesTableSeeder::class,
            CountriesSeeder::class,
            DietMasterTableSeeder::class,
            DocumentSourceMasterTableSeeder::class,
            SpecialityTableSeeder::class,
            GenderSeeder::class,
            TimezonesTableSeeder::class,
            VitalSeeder::class,
            DynamicFormsMasterTableSeeder::class,
            DynamicFormsTableSeeder::class,
            AssessmentGroupSeeder::class,
            HistorySeeder::class,
            HealthSeeder::class,
            ImmunisationSeeder::class,
            ConsultMenuSeeder::class,
            SuperAdminUserSeeder::class,
            AssementFormSeeder::class,
            AllergySeeder::class,
            ReactionSeeder::class,
            ConditionSeeder::class,
            ProcedureSeeder::class,
            ActivitySeeder::class,
            OccupationSeeder::class,
            LivingRelationshipSeeder::class,
            ImagingSeeder::class,
            LabSeeder::class,
        ]);
    }
}
