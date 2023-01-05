<?php

namespace Database\Seeders;

use App\Entities\Question;
use DB;
use Illuminate\Database\Seeder;

class UpdateAssessmentStrokeScale extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
        
        $update_questions = [
         	['match_value'=> 'NIHSS >25','name' =>'NIHSS >4 and <22'],
         	['match_value'=> 'Abnormal blood glucose (<50 mg/dL)','name' =>'Abnormal blood glucose (<50 mg/dL and > 400 mg/dL)'],
         	['match_value'=> 'Suspected/confirmed endocarditis','name' =>'Atrial Fibrillation / Suspected or confirmed endocarditis / recent ST segemnt elevation MI'],
         	['match_value'=> 'Any active anticoagulant use (even with INR <1.7)','name' =>'Any active anticoagulant use (even with INR >1.7)'],
        ];


        foreach ($update_questions as $key => $value) {

        	$match_value = $value['match_value'];
        	unset($value['match_value']);
            DB::table('questions')
                ->where('name',$match_value)
                ->update($value);

        }

        $questions = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);


		$current_question_id = $questions["Additional Warnings to TPA >3hr Onset"];
		$radio_type 			= 'radio';
		$new_sub_questions = [
		 ['parent_id' => $current_question_id, 'name' =>
		 	'NIHSS <4 and no dysphasia or rapidly improving symptoms','type' => $radio_type,'is_active' => 1]
		];
		DB::table('questions')->insert($new_sub_questions);

    }
}
