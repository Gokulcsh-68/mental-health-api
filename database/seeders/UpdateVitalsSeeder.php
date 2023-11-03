<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateVitalsSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {  
        $master_types = [
            [ 'master_type_slug' => 'vitals',
        		'name' => 'Respiration Rate', 'slug' => 'respiration'],
            [ 'master_type_slug' => 'speciality',
        		'name' => 'Internist / Infection Disease Specialist (COVID19) ', 'slug' => 'infection_disease_specialist'],
            [ 'master_type_slug' => 'vitals','name' => 'HCT', 'slug' => 'hct'],
            [ 'master_type_slug' => 'vitals', 'name' => 'Hemoglobin', 'slug' => 'hemoglobin'],
            [ 'master_type_slug' => 'vitals', 'name' => 'Keytone', 'slug' => 'keytone'],
            [ 'master_type_slug' => 'vitals', 'name' => 'Uric Acid', 'slug' => 'uric_acid'],
            [ 'master_type_slug' => 'vitals', 'name' => 'Spirometer', 'slug' => 'spirometer'],
            [ 'master_type_slug' => 'health', 'name' => 'Dental', 'slug' => 'dental'],
        ];
        
        DB::table('masters')->insertOrIgnore($master_types);
    }

} 