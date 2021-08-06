<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class VDXSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'vdx'],
            [ 'slug' => 'vdx_sub_types'],
        ];
        
        DB::table('master_types')->insert($master_types);

        $vdx = [
            ['master_type_slug' => 'vdx', 'name' =>'Genito Urinary', 'slug' =>'vdx_genito_urinary', 'is_active' => 1],
            ['master_type_slug' => 'vdx', 'name' =>'Cardiac & Pulmonary', 'slug' =>'vdx_cardiac_pulmonary', 'is_active' => 1],
            ['master_type_slug' => 'vdx', 'name' =>'Skin Lesions', 'slug' =>'vdx_skin_lesions', 'is_active' => 1],
            ['master_type_slug' => 'vdx', 'name' =>'Neurology & Psychiatric', 'slug' =>'vdx_neurology_psychiatric', 'is_active' => 1],
            ['master_type_slug' => 'vdx', 'name' =>'Ophthalmology', 'slug' =>'vdx_ophthalmology', 'is_active' => 1],
            ['master_type_slug' => 'vdx', 'name' =>'ENT & Oral Medicine', 'slug' =>'vdx_ent_oral_medicine', 'is_active' => 1],
            ['master_type_slug' => 'vdx', 'name' =>'Gastrointestinal', 'slug' =>'vdx_gastrointestinal', 'is_active' => 1]
        ];

        DB::table('masters')->insert($vdx);
    }
}
