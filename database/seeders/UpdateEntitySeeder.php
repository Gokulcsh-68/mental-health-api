<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateEntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$entity = [
            ['key' => '9p1JM1ECevfApPSWHxS9GlwGW8WHOCMa', 'end_url' => 'http://127.0.0.1:92/', 'entity_name' => 'Covid','is_active'=> 1],
    	];

        DB::table('entity')->insert($entity);
    }
}
