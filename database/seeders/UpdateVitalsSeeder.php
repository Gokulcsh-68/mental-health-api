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
        ];
        
        DB::table('masters')->insert($master_types);
    }

} 