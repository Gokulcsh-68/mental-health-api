<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
    		[
                'master_type_slug' => 'country', 
                'slug' => 'US', 
                'name' => 'United States',
                'attributes' => json_encode(['phonecode' => '+1','currency_code' => 'USD','currency_symbol' => '$']),
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'country', 
                'slug' => 'IN', 
                'name' => 'India',
                'attributes' => json_encode(['phonecode' => '+91','currency_code' => 'INR','currency_symbol' => '₹']),
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'country', 
                'slug' => 'AE', 
                'name' => 'United Arab Emirates',
                'attributes' => json_encode(['phonecode' => '+971','currency_code' => 'AED','currency_symbol' => 'AED']),
                'is_active' => 1,
            ],
    	];

        DB::table('masters')->insert($countries);
    }
}
