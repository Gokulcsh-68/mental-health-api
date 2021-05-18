<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssessmentCovidTriageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $forms = [
            [
                'name' => 'Triage (SOP) in Covid 19', 
                'desc' => 'Triage (SOP) in Covid 19', 
                'assessment_group' => 'covid', 
                'type' => 'score', 
                'slug' => 'covid_triage', 
                'role_code' => '["admin", "provider", "school", "staff", "student"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);


        $insert_questions = [
            [
                'name' => 'PRE / ASYMPTOMATIC', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'MILD ILLNESS', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'MODERATE ILLNESS', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'SEVERE ILLNESS', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'CRITICAL ILLNESS', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);


        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["Triage (SOP) in Covid 19"];

        $form_questions = [
            ['question_id' => $questions["PRE / ASYMPTOMATIC"],'form_id' => $current_form_id],
            ['question_id' => $questions["MILD ILLNESS"],'form_id' => $current_form_id],
            ['question_id' => $questions["MODERATE ILLNESS"],'form_id' => $current_form_id],
            ['question_id' => $questions["SEVERE ILLNESS"],'form_id' => $current_form_id],
            ['question_id' => $questions["CRITICAL ILLNESS"],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['PRE / ASYMPTOMATIC'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 
                        'Test +ve(positive)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Symptoms that are consistant with Covid19','type'=> $radio_type,'is_active' => 1],
        ];

        DB::table('questions')->insert($new_sub_questions);

        // 2 Sub question
        $current_question_id = $questions['MILD ILLNESS'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 
                        'Symptoms of Fever, Cough, Soar Throat, Fatigue, Head ache, Body ache, loss of smell & or Taste etc','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Shortness of breath','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Difficulty in breathing','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Abnormal chest imaging','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Current Mental Conditions','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Co-morbidity like diabetes, hypertension, cardiac, hepatic, renal, etc conditions','type'=> $radio_type,'is_active' => 1],

        ];

        DB::table('questions')->insert($new_sub_questions);

         // 3 Sub question
        $current_question_id = $questions['MODERATE ILLNESS'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 
                        'Evidence of lower respiratory infections','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Positive Chest Imaging','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Positive clinical signs on evaluation / symptomatic assessment','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'SpO2 at <=94%','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Cough with Expectoration','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Sputum (may be colored or blood stained)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Increased in respiration rate > 20 BPM','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Shortness or Difficulty in Breathing','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Pain or burning in the Chest','type'=> $radio_type,'is_active' => 1],

        ];

        DB::table('questions')->insert($new_sub_questions);

         // 4 Sub question
        $current_question_id = $questions['SEVERE ILLNESS'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 
                        'SpO2 < 94%','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Respiratory Rate > 30 BPM','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Imaging Lung infilltrates > 50%','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Po2 / F1o2 (Less than 300mm Hg)','type'=> $radio_type,'is_active' => 1],
        ];

        DB::table('questions')->insert($new_sub_questions);

         // 5 Sub question
        $current_question_id = $questions['CRITICAL ILLNESS'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 
                        'Respiratory Failure','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Septic shock','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'Multiple Organ Dysfunction','type'=> $radio_type,'is_active' => 1],
        ];

        DB::table('questions')->insert($new_sub_questions);


        $current_question_id    = $questions['PRE / ASYMPTOMATIC'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);

         $current_question_id    = $questions['MILD ILLNESS'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);

          $current_question_id    = $questions['MODERATE ILLNESS'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);

          $current_question_id    = $questions['SEVERE ILLNESS'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);

          $current_question_id    = $questions['CRITICAL ILLNESS'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);


    }
}
