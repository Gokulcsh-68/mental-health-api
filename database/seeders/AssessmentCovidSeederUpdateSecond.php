<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssessmentCovidSeederUpdateSecond extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $questions['Are you having any of these problems?'];

        // 16
        # Are you having any of these problems? Sub Question
        $current_question_id = $questions['Are you having any of these problems?'];
        $radio_type          = 'radio';

        Question::where('parent_id',$current_question_id)->delete();

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 'Cancer','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Hypertension / Blood Pressure','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Cerebrovascular disease','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Chronic kidney disease','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Chronic obstructive pulmonary disease (COPD) and other lung disease (including interstitial lung disease, pulmonary fibrosis, pulmonary hypertension)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Diabetes mellitus, type 1 and type 2','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Down syndrome','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Heart conditions (such as heart failure, coronary artery disease, or cardiomyopathies)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'HIV','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Neurologic conditions, including dementia','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Overweight and obesity (BMI ≥25 kg/m2)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Pregnancy','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Sickle cell disease','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Smoking, current and former','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Solid organ or blood stem cell transplantation','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Substance use disorders','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Use of corticosteroids or other immunosuppressive medications','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Cystic fibrosis','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Thalassemia','type'=> $radio_type,'is_active' => 1]
        ];

        DB::table('questions')->insert($new_sub_questions);


    }
}
