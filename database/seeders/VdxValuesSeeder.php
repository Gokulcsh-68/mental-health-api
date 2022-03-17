<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VdxValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            VdxDeleteOldSeeder::class,
            VDXSeeder::class,
            UpdateVitalsSeeder::class,
            CardiacPulmonarySeeder::class,
            EntOralMedicineSeeder::class,
            GastrointestinalSeeder::class,
            GenitoUrinarySeeder::class,
            NeurologyPsychiatricSeeder::class,
            OphthalmologySeeder::class,
            SkinLesionsSeeder::class,
            SymptomsReasonSeeder::class,
            SymptomsValueReasonSeeder::class,
            ROSTableSeeder::class,
            UpdateROSSeeder::class

        ]);
    }
}
