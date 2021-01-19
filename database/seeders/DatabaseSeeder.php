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
            SpecialityTableSeeder::class,
            GenderSeeder::class,
            TimezonesTableSeeder::class,
            VitalSeeder::class,
            dynamicFormsMasterTableSeeder::class,
            dynamicFormsTableSeeder::class,
            AssessmentGroupSeeder::class,
            HistorySeeder::class,
            HealthSeeder::class,
            ImmunisationSeeder::class,
            ConsultMenuSeeder::class,
            SuperAdminUserSeeder::class,
            AssementFormSeeder::class,
            AllergySeeder::class,
        ]);

        #ini_set('memory_limit', '-1');
        #\DB::unprepared(file_get_contents(__dir__ . '\source\AssessmentDump.sql'));
    }
}
