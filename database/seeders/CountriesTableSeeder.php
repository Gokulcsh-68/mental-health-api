<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // master, m.type
        $countries = [
    		['id' => 1, 'iso' => 'US','name' => 'United States','phonecode' => '+1','currency_code' => 'USD','currency_symbol' => '$'],
    		['id' => 2, 'iso' => 'IN','name' => 'India','phonecode' => '+91','currency_code' => 'INR','currency_symbol' => '₹'],
    		['id' => 3, 'iso' => 'AE','name' => 'United Arab Emirates','phonecode' => '+971','currency_code' => 'AED','currency_symbol' => 'AED'],
    	];

        DB::table('countries')->insert($countries);
    }
}
