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
        
        DB::table('master_types')->insertOrIgnore($master_types);

        $vdx = [
            ['master_type_slug' => 'vdx', 'name' =>'Genito Urinary', 'slug' =>'vdx_genito_urinary', 'is_active' => 1,'attributes' => json_encode(['speciality'=>['gynecology','andrology']])],
            ['master_type_slug' => 'vdx', 'name' =>'Cardiac & Pulmonary', 'slug' =>'vdx_cardiac_pulmonary', 'is_active' => 1,'attributes' =>json_encode(['speciality'=>['cardiology','pulmonolgy']])],
            ['master_type_slug' => 'vdx', 'name' =>'Skin Lesions', 'slug' =>'vdx_skin_lesions', 'is_active' => 1,'attributes' =>json_encode(['speciality'=>['dermatology']])],
            ['master_type_slug' => 'vdx', 'name' =>'Neurology & Psychiatric', 'slug' =>'vdx_neurology_psychiatric', 'is_active' => 1,'attributes' =>json_encode(['speciality'=>['neurology','psychiatry']])],
            ['master_type_slug' => 'vdx', 'name' =>'Ophthalmology', 'slug' =>'vdx_ophthalmology', 'is_active' => 1,'attributes' =>json_encode(['speciality'=>['opthalmology']])],
            ['master_type_slug' => 'vdx', 'name' =>'ENT & Oral Medicine', 'slug' =>'vdx_ent_oral_medicine', 'is_active' => 1,'attributes' =>json_encode(['speciality'=>['otolarynglogy','otorhinolaryngology']])],
            ['master_type_slug' => 'vdx', 'name' =>'Gastrointestinal', 'slug' =>'vdx_gastrointestinal', 'is_active' => 1,'attributes' =>json_encode(['speciality'=>['gastroenterology']])]
        ];

        // DB::table('masters')->insert($vdx);

        foreach ($vdx as $key => $value) {

            $matchThese = ['slug'=>$value['slug'],'master_type_slug'=>$value['master_type_slug']];

            $chk = DB::table('masters')->where('slug',$value['slug'])->where('master_type_slug',$value['master_type_slug'])->value('id');

            if(!empty($chk)){
            DB::table('masters')->where('id',$chk)->update($value);

            }else{
            DB::table('masters')->insert($value);

            }
        }
    }
}
