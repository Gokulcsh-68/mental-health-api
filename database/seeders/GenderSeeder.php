<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gendral_gender_status = $global_gender_status = 1;

    	$genders = [
            ['master_type_slug' => 'gender', 'slug' => 'male', 'name' => "Male", 'is_active' => $gendral_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'female', 'name' => "Female", 'is_active' => $gendral_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'others', 'name' => "Others", 'is_active' => $gendral_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'agender', 'name' => "Agender", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'androgyne', 'name' => "Androgyne", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'androgynous', 'name' => "Androgynous", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'bigender', 'name' => "Bigender", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cis', 'name' => "Cis", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cis-female', 'name' => "Cis Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cis-male', 'name' => "Cis Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cis-man', 'name' => "Cis Man", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cis-woman', 'name' => "Cis Woman", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cisgender-female', 'name' => "Cisgender Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cisgender-male', 'name' => "Cisgender Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cisgender-man', 'name' => "Cisgender Man", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'cisgender-woman', 'name' => "Cisgender Woman", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'female-to-male', 'name' => "Female to Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'ftm', 'name' => "FTM", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'gender-fluid', 'name' => "Gender Fluid", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'gender-nonconforming', 'name' => "Gender Nonconforming", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'gender-questioning', 'name' => "Gender Questioning", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'gender-variant', 'name' => "Gender Variant", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'genderqueer', 'name' => "Genderqueer", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'intersex', 'name' => "Intersex", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'male-to-female', 'name' => "Male to Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'mtf', 'name' => "MTF", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'neither', 'name' => "Neither", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'neutrois', 'name' => "Neutrois", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'non-binary', 'name' => "Non-binary", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'pangender', 'name' => "Pangender", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans', 'name' => "Trans", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-star', 'name' => "Trans*", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-female', 'name' => "Trans Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-star-female', 'name' => "Trans* Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-male', 'name' => "Trans Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-star-male', 'name' => "Trans* Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-man', 'name' => "Trans Man", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-star-man', 'name' => "Trans* Man", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-person', 'name' => "Trans Person", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-star-person', 'name' => "Trans* Person", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-woman', 'name' => "Trans Woman", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'trans-star-woman', 'name' => "Trans* Woman", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transfeminine', 'name' => "Transfeminine", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transgender', 'name' => "Transgender", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transgender-female', 'name' => "Transgender Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transgender-male', 'name' => "Transgender Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transgender-man', 'name' => "Transgender Man", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transgender-person', 'name' => "Transgender Person", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transgender-woman', 'name' => "Transgender Woman", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transmasculine', 'name' => "Transmasculine", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transsexual', 'name' => "Transsexual", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transsexual-female', 'name' => "Transsexual Female", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transsexual-male', 'name' => "Transsexual Male", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transsexual-man', 'name' => "Transsexual Man", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transsexual-person', 'name' => "Transsexual Person", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'transsexual-woman', 'name' => "Transsexual Woman", 'is_active' => $global_gender_status],
            ['master_type_slug' => 'gender', 'slug' => 'two-spirit', 'name' => "Two-Spirit", 'is_active' => $global_gender_status],
    	];

    	DB::table('masters')->insert($genders);
    }
}
