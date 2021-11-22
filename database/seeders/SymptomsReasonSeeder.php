<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SymptomsReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'symptoms_reason']
        ];
        
        DB::table('master_types')->insertOrIgnore($master_types);

        $symptoms_reason = [
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Heart and Lungs', 'slug' => str_slug("Heart and Lungs"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Ear, Nose, Throat and Mouth', 'slug' => str_slug("Ear, Nose, Throat and Mouth"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Stomach and Intestines', 'slug' => str_slug("Stomach and Intestines"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Urinary and reproductive', 'slug' => str_slug("Urinary and reproductive"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Male genital', 'slug' => str_slug("Male genital"),'is_active' => 1,'attributes' => json_encode(['gender'=>'male'])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Female genital', 'slug' => str_slug("Female genital"),'is_active' => 1,'attributes' => json_encode(['gender'=>'female'])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Nerves and Mental health', 'slug' => str_slug("Nerves and Mental health"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Eyes', 'slug' => str_slug("Eyes"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])],
            ['master_type_slug' => 'symptoms_reason', 'name' => 'Skin', 'slug' => str_slug("Skin"),'is_active' => 1,'attributes' => json_encode(['gender'=>''])]
        ];

        // DB::table('masters')->insert($symptoms_reason);

        foreach ($symptoms_reason as $key => $value) {
        	
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
