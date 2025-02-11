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
            ['attributes' => json_encode(['reference_slug' => 'heart-and-lungs']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Difficulty while sleeping', 'slug' => str_slug("Difficulty while sleeping"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Ulcers in the mouth', 'slug' => str_slug("Ulcers in the mouth"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Ear pain', 'slug' => str_slug("Ear pain"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Sore throat', 'slug' => str_slug("Sore throat"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Difficulty swallowing', 'slug' => str_slug("Difficulty swallowing"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Running nose', 'slug' => str_slug("Running nose"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Toothache', 'slug' => str_slug("Toothache"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Nasal Congestion', 'slug' => str_slug("Nasal Congestion"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Ringing in the ears', 'slug' => str_slug("Ringing in the ears"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Burning sensation in the mouth', 'slug' => str_slug("Burning sensation in the mouth"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Change in taste sensation', 'slug' => str_slug("Change in taste sensation"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'ear-nose-throat-and-mouth']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Difficulty chewing', 'slug' => str_slug("Difficulty chewing"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Stomach pain', 'slug' => str_slug("Stomach pain"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Loose Stools', 'slug' => str_slug("Loose Stools"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Vomiting', 'slug' => str_slug("Vomiting"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Nausea', 'slug' => str_slug("Nausea"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Tenderness around stomach', 'slug' => str_slug("Tenderness around stomach"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Constipation', 'slug' => str_slug("Constipation"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Bloating', 'slug' => str_slug("Bloating"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Blood in stools', 'slug' => str_slug("Blood in stools"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Jaundice', 'slug' => str_slug("Jaundice"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Loss of appetite', 'slug' => str_slug("Loss of appetite"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Increased appetite', 'slug' => str_slug("Increased appetite"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'stomach-and-intestines']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Increased burping & gas', 'slug' => str_slug("Increased burping & gas"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'urinary-and-reproductive']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Increased urination', 'slug' => str_slug("Increased urination"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'urinary-and-reproductive']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Decreased urination', 'slug' => str_slug("Decreased urination"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'urinary-and-reproductive']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Unable to hold urine', 'slug' => str_slug("Unable to hold urine"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'urinary-and-reproductive']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Blood in urine', 'slug' => str_slug("Blood in urine"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'urinary-and-reproductive']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Pelvic pain', 'slug' => str_slug("Pelvic pain"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'male-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Erectile dysfunction', 'slug' => str_slug("Erectile dysfunction"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'male-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Burning on urination', 'slug' => str_slug("Burning on urination"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'male-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Change in urine color', 'slug' => str_slug("Change in urine color"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'male-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Redness, swelling or irritation', 'slug' => str_slug("Redness, swelling or irritation"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'female-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Missed periods', 'slug' => str_slug("Missed periods"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'female-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Increased menstrual blood', 'slug' => str_slug("Increased menstrual blood"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'female-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Absence of menstruation', 'slug' => str_slug("Absence of menstruation"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'female-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Discharge', 'slug' => str_slug("Discharge"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'female-genital']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Pain,swelling or irritation', 'slug' => str_slug("Pain,swelling or irritation"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Headache', 'slug' => str_slug("Headache"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Fainting', 'slug' => str_slug("Fainting"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Dizziness', 'slug' => str_slug("Dizziness"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Loss of sensation anywhere', 'slug' => str_slug("Loss of sensation anywhere"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Double vision', 'slug' => str_slug("Double vision"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Difficulty in movements & walking', 'slug' => str_slug("Difficulty in movements & walking"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Foot drop', 'slug' => str_slug("Foot drop"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Depression and sadness', 'slug' => str_slug("Depression and sadness"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'nerves-and-mental-health']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Sudden or frequent mood swings', 'slug' => str_slug("Sudden or frequent mood swings"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Pain, redness or irritation', 'slug' => str_slug("Pain, redness or irritation"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Eye pain', 'slug' => str_slug("Eye pain"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Double vision', 'slug' => str_slug("Double vision"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Loss of vision', 'slug' => str_slug("Loss of vision"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Increased dryness or tear production', 'slug' => str_slug("Increased dryness or tear production"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Use of glasses or contact lens', 'slug' => str_slug("Use of glasses or contact lens"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'eyes']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Discharge from eyes', 'slug' => str_slug("Discharge from eyes"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Fever / Rashes', 'slug' => str_slug("Fever / Rashes"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Scaly skin', 'slug' => str_slug("Scaly skin"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Raised patches', 'slug' => str_slug("Raised patches"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Boils, burns or swellings', 'slug' => str_slug("Boils, burns or swellings"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'itching', 'slug' => str_slug("itching"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Pus or other discharge', 'slug' => str_slug("Pus or other discharge"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'skin']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Moles, Dark or light patches', 'slug' => str_slug("Moles, Dark or light patches"),'is_active' => 1],

            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Lethargy', 'slug' => str_slug("Lethargy"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Unexplained weight gain or weight loss', 'slug' => str_slug("unexplained weight gain or weight loss"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Loss of appetite', 'slug' => str_slug("loss of appetite"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Fever', 'slug' => str_slug("fever"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Fatigue', 'slug' => str_slug("fatigue"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Anorexia', 'slug' => str_slug("anorexia"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Night Sweats', 'slug' => str_slug("night sweats"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Scalp Tenderness', 'slug' => str_slug("scalp tenderness"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Prior diagnosis of cancer', 'slug' => str_slug("prior diagnosis of cancer"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Malaise', 'slug' => str_slug("malaise"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Ability to conduct usual activities', 'slug' => str_slug("Ability to conduct usual activities"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Exercise tolerance', 'slug' => str_slug("Exercise tolerance"),'is_active' => 1],
            ['attributes' => json_encode(['reference_slug' => 'constitutional']),'master_type_slug' => 'symptoms_reason_sub_types', 'name' => 'Sense of well-being', 'slug' => str_slug("Sense of well-being"),'is_active' => 1]
        ];

        // DB::table('masters')->insert($symptoms_reason);

        foreach ($symptoms_reason as $key => $value) {
        	
            $matchThese = ['slug'=>$value['slug'],'master_type_slug'=>$value['master_type_slug']];

            $chk = DB::table('masters')->where('slug',$value['slug'])->where('master_type_slug',$value['master_type_slug'])->value('id');

            if(!empty($chk)){
            DB::table('masters')->where('id',$chk)->update($value);

            }else{
            DB::table('masters')->insertOrIgnore($value);

            }
        }
    }
}
