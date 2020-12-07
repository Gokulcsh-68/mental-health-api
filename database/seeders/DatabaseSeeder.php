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
            SpecialityTableSeeder::class,
            MasterTypesTableSeeder::class,
            CountriesSeeder::class,
            GenderSeeder::class,
            TimezonesTableSeeder::class,
            VitalSeeder::class,
        ]);
    }
}
