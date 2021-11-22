<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SymptomsValueReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'symptoms_reason_sub_types']
        ];
        
        DB::table('master_types')->insertOrIgnore($master_types);

        $symptoms_reason = [
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Chest Pain', 'slug' => str_slug("Chest Pain"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Difficulty breathing', 'slug' => str_slug("Difficulty breathing"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Cough', 'slug' => str_slug("Cough"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Heart palpitations', 'slug' => str_slug("Heart palpitations"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Wheezing', 'slug' => str_slug("Wheezing"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Increased heart rate', 'slug' => str_slug("Increased heart rate"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Coughing up blood', 'slug' => str_slug("Coughing up blood"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Heartburn', 'slug' => str_slug("Heartburn"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Tightness of chest', 'slug' => str_slug("Tightness of chest"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Labored breathing', 'slug' => str_slug("Labored breathing"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Difficulty while sleeping', 'slug' => str_slug("Difficulty while sleeping"),'is_active' => 1]
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
