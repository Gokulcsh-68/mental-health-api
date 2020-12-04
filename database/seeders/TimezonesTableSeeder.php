<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class TimezonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timezones = [
    		['zone_name' => 'Asia/Kolkata', 'country_code' => 'IN'],
    		['zone_name' => 'Asia/Dubai', 'country_code' => 'AE'],

			['zone_name' => "America/New_York", 'country_code' => 'US'],
			['zone_name' => "America/Detroit", 'country_code' => 'US'],
			['zone_name' => "America/Kentucky/Louisville", 'country_code' => 'US'],
			['zone_name' => "America/Kentucky/Monticello", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Indianapolis", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Vincennes", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Winamac", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Marengo", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Petersburg", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Vevay", 'country_code' => 'US'],
			['zone_name' => "America/Chicago", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Tell_City", 'country_code' => 'US'],
			['zone_name' => "America/Indiana/Knox", 'country_code' => 'US'],
			['zone_name' => "America/Menominee", 'country_code' => 'US'],
			['zone_name' => "America/North_Dakota/Center", 'country_code' => 'US'],
			['zone_name' => "America/North_Dakota/New_Salem", 'country_code' => 'US'],
			['zone_name' => "America/North_Dakota/Beulah", 'country_code' => 'US'],
			['zone_name' => "America/Denver", 'country_code' => 'US'],
			['zone_name' => "America/Boise", 'country_code' => 'US'],
			['zone_name' => "America/Phoenix", 'country_code' => 'US'],
			['zone_name' => "America/Los_Angeles", 'country_code' => 'US'],
			['zone_name' => "America/Anchorage", 'country_code' => 'US'],
			['zone_name' => "America/Juneau", 'country_code' => 'US'],
			['zone_name' => "America/Sitka", 'country_code' => 'US'],
			['zone_name' => "America/Metlakatla", 'country_code' => 'US'],
			['zone_name' => "America/Yakutat", 'country_code' => 'US'],
			['zone_name' => "America/Nome", 'country_code' => 'US'],
			['zone_name' => "America/Adak", 'country_code' => 'US'],
			['zone_name' => "Pacific/Honolulu", 'country_code' => 'US'],
    	];

        DB::table('timezones')->insert($timezones);
    }
}
