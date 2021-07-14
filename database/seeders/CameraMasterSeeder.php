<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CameraMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            ['slug' => 'camera'],
        ];

        DB::table('masters')
            ->whereIn('master_type_slug', array_values(($master_types)) )
            ->delete();
        
        DB::table('master_types')
            ->whereIn('slug', array_values($master_types) )
            ->delete();

        DB::table('master_types')->insert($master_types);

        $cameras = [
    		[
                'master_type_slug' => 'camera', 
                'slug' => 'minrray', 
                'name' => 'Minrray',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ]
    	];

        DB::table('masters')->insert($cameras);
    }
}
