<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateAllergySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $health_types = [
    		['master_type_slug' => 'allergy',
            'name' => "Prawn",
            'slug' => "prawn",
            'attributes' => json_encode([ 'allergy_type' => "Sea Food", 'allergy_category' => "Food"]),'is_active' => 1],
            ['master_type_slug' => 'allergy',
            'name' => "Shrimp",
            'slug' => "shrimp",
            'attributes' => json_encode([ 'allergy_type' => "Sea Food", 'allergy_category' => "Food"]),'is_active' => 1]      
    	];


        DB::table('masters')
            ->where('master_type_slug','Generic')
            ->update(["attributes"=>json_encode([ 'allergy_type' => "Drug", 'allergy_category' => "Drug"])]);


        DB::table('masters')
            ->where('master_type_slug','medicine-us')
            ->update(["attributes"=>json_encode([ 'allergy_type' => "Drug", 'allergy_category' => "Drug"])]);

        foreach ($health_types as $key => $value) {
            $chk = DB::table('masters')
                ->where('slug',$value['slug'])
                ->where('master_type_slug','allergy')
                ->count();

                if($chk > 0){
                    DB::table('masters')
                        ->where('slug',$value['slug'])
                        ->where('master_type_slug','allergy')
                        ->update($value);
                }
                else{
                    DB::table('masters')->insert($value);

                }
        }
    }
}
