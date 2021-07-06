<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$entity = [
    		['key' => 'DUMUqJrNeyuaPDSZSsMf91qjgwc5K3N7', 'end_url' => 'http://127.0.0.1:89/', 'entity_name' => 'A2ZHealth','is_active'=> 1],
            ['key' => 'j5mFs9ZvolQ9ijUHNon0s513AG8CUMvw', 'end_url' => 'http://127.0.0.1:90/', 'entity_name' => 'Garuda','is_active'=> 1],
    	];

        DB::table('entity')->insert($entity);
    }
}
