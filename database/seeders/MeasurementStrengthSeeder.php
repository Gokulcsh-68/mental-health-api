<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MeasurementStrengthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            ['slug' => 'measurement-strength']
        ];
        
        DB::table('master_types')->insert($master_types);

        $measurement_strength = [
            ['master_type_slug' => 'measurement-strength', 'name' => 'Colony forming units per milliliter (cfu/ml)',
             'slug' => str_slug('Colony forming units per milliliter (cfu/ml)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'International unit (iu)',
             'slug' => str_slug('International unit (iu)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'Milliequivalent(meq)',
             'slug' => str_slug('Milliequivalent(meq)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'Milliequivalent per liter (meq/ml)',
             'slug' => str_slug('Milliequivalent per liter (meq/ml)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'Milligram(mg)',
             'slug' => str_slug('Milligram(mg)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'Milligram per milliliter',
             'slug' => str_slug('Milligram per milliliter'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'milliliter(ml)',
             'slug' => str_slug('milliliter(ml)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'percentage(%)',
             'slug' => str_slug('percentage(%)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'Unit  (unt)',
             'slug' => str_slug('Unit  (unt)'), 'is_active' => 1],
            ['master_type_slug' => 'measurement-strength', 'name' => 'Unit per milliliter (unt/ml)',
             'slug' => str_slug('Unit per milliliter (unt/ml)'), 'is_active' => 1]
    	];

        DB::table('masters')->insert($measurement_strength);
    }
}
