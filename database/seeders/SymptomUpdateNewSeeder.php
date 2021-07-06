<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SymptomUpdateNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $sypmtoms = [
            ['master_type_slug' => 'symptom', 'name' => 'Numbeness of Feet or Legs', 'slug' => str_slug("Numbeness of Feet or Legs"),
              'attributes' => json_encode(['link' => ""])],
            ['master_type_slug' => 'symptom', 'name' => 'Blurred Vision', 'slug' => str_slug("Blurred Vision"),
              'attributes' => json_encode(['link' => ""])],
            ['master_type_slug' => 'symptom', 'name' => 'Delayed healing of wounds', 'slug' => str_slug("Delayed healing of wounds"),
              'attributes' => json_encode(['link' => ""])],
            ['master_type_slug' => 'symptom', 'name' => 'Itchy Skin', 'slug' => str_slug("Itchy Skin"),
              'attributes' => json_encode(['link' => ""])],
    	];

        DB::table('masters')->insert($sypmtoms);
    }
}
