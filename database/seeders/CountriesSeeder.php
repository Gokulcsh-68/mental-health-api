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
        DB::table('masters')
        ->where('master_type_slug', 'country')
        ->delete();
        $countries = [
            [
                'master_type_slug' => 'country', 
                'slug' => 'US', 
                'name' => 'United States',
                'attributes' => json_encode([
                    'phonecode' => '+1',
                    'currency_code' => 'USD',
                    'currency_symbol' => '$',
                    'states' => [
                        'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 
                        'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia',
                        'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa',
                        'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland',
                        'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri',
                        'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey',
                        'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio',
                        'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina',
                        'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
                        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
                    ]
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'country', 
                'slug' => 'IN', 
                'name' => 'India',
                'attributes' => json_encode([
                    'phonecode' => '+91',
                    'currency_code' => 'INR',
                    'currency_symbol' => '₹',
                    'states' => [
                        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
                        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
                        'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
                        'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
                        'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
                        'Uttar Pradesh', 'Uttarakhand', 'West Bengal'
                    ]
                ]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'country', 
                'slug' => 'AE', 
                'name' => 'United Arab Emirates',
                'attributes' => json_encode([
                    'phonecode' => '+971',
                    'currency_code' => 'AED',
                    'currency_symbol' => 'AED',
                    'states' => [
                        'Abu Dhabi', 'Dubai', 'Sharjah', 'Ajman', 
                        'Umm Al Quwain', 'Fujairah', 'Ras Al Khaimah'
                    ]
                ]),
                'is_active' => 1,
            ],
        ];

        DB::table('masters')->insert($countries);
    }
}
