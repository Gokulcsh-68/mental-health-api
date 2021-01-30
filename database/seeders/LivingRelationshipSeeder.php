<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class LivingRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'living_relationship']
        ];
        
        DB::table('master_types')->insert($master_types);

        $relationships = [
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives with spouse', 'slug' =>'Lives with spouse', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives alone, independent', 'slug' =>'Lives alone, independent', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives with son', 'slug' =>'Lives with son', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives with daughter', 'slug' =>'Lives with daughter', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives alone, children live near by', 'slug' =>'Lives alone, children live near by', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives alone, neighbours help', 'slug' =>'Lives alone, neighbours help', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives in Nursing Home', 'slug' =>'Lives in Nursing Home', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives in group home', 'slug' =>'Lives in group home', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives in assisted living facility', 'slug' =>'Lives in assisted living facility', 'is_active' => 1],
            ['master_type_slug' => 'living_relationship', 'name' =>'Lives with family', 'slug' =>'Lives with family', 'is_active' => 1]
        ];

        DB::table('masters')->insert($relationships);
    }
}
