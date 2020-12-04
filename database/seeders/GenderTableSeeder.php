<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$genders = [
    		['name' => "Male"],
    		['name' => "Female"],
    		['name' => "Others"],
    		['name' => "Agender"],
    		['name' => "Androgyne"],
    		['name' => "Androgynous"],
    		['name' => "Bigender"],
    		['name' => "Cis"],
    		['name' => "Cis Female"],
    		['name' => "Cis Male"],
    		['name' => "Cis Man"],
    		['name' => "Cis Woman"],
    		['name' => "Cisgender Female"],
    		['name' => "Cisgender Male"],
    		['name' => "Cisgender Man"],
    		['name' => "Cisgender Woman"],
    		['name' => "Female to Male"],
    		['name' => "FTM"],
    		['name' => "Gender Fluid"],
    		['name' => "Gender Nonconforming"],
    		['name' => "Gender Questioning"],
    		['name' => "Gender Variant"],
    		['name' => "Genderqueer"],
    		['name' => "Intersex"],
    		['name' => "Male to Female"],
    		['name' => "MTF"],
    		['name' => "Neither"],
    		['name' => "Neutrois"],
    		['name' => "Non-binary"],
    		['name' => "Pangender"],
    		['name' => "Trans"],
    		['name' => "Trans*"],
    		['name' => "Trans Female"],
    		['name' => "Trans* Female"],
    		['name' => "Trans Male"],
    		['name' => "Trans* Male"],
    		['name' => "Trans Man"],
    		['name' => "Trans* Man"],
    		['name' => "Trans Person"],
    		['name' => "Trans* Person"],
    		['name' => "Trans Woman"],
    		['name' => "Trans* Woman"],
    		['name' => "Transfeminine"],
    		['name' => "Transgender"],
    		['name' => "Transgender Female"],
    		['name' => "Transgender Male"],
    		['name' => "Transgender Man"],
    		['name' => "Transgender Person"],
    		['name' => "Transgender Woman"],
    		['name' => "Transmasculine"],
    		['name' => "Transsexual"],
    		['name' => "Transsexual Female"],
    		['name' => "Transsexual Male"],
    		['name' => "Transsexual Man"],
    		['name' => "Transsexual Person"],
    		['name' => "Transsexual Woman"],
    		['name' => "Two-Spirit"],
    	];

    	DB::table('genders')->insert($genders);
    }
}
