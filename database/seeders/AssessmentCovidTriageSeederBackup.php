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
                'slug' => 'covid_investigation_biochemical', 
                'role_code' => '["admin", "provider", "school", "staff", "student"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);


        $insert_questions = [
            [
                'name' => 'PRE / ASYMPTOMATIC', 
                'type' => 'checkbox',
                'is_active' => 1,
            ],
            [
                'name' => 'MILD ILLNESS', 
                'type' => 'checkbox',
                'is_active' => 1,
            ],
            [
                'name' => 'MODERATE ILLNESS', 
                'type' => 'checkbox',
                'is_active' => 1,
            ],
            [
                'name' => 'SEVERE ILLNESS', 
                'type' => 'checkbox',
                'is_active' => 1,
            ],
            [
                'name' => 'CRITICAL ILLNESS', 
                'type' => 'checkbox',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'Test +ve(positive)',
                'is_active' => 1,
            ],
            [
                'name' => 'No Symptoms that are consistant with Covid19',
                'is_active' => 1,
            ],
            [
                'name' => 'Symptoms of Fever, Cough, Soar Throat, Fatigue, Head ache, Body ache, loss of smell & or Taste etc',
                'is_active' => 1,
            ],
            [
                'name' => 'No shortness of breath',
                'is_active' => 1,
            ],
            [
                'name' => 'No difficulty in breathing',
                'is_active' => 1,
            ],
            [
                'name' => 'No abnormal chest imaging',
                'is_active' => 1,
            ],
            [
                'name' => 'No Current Mental Conditions',
                'is_active' => 1,
            ],
            [
                'name' => 'Correlate with No co-morbidity if any',
                'is_active' => 1,
            ],
            [
                'name' => 'Evidence of lower respiratory infections',
                'is_active' => 1,
            ],
            [
                'name' => 'Positive Chest Imaging',
                'is_active' => 1,
            ],
            [
                'name' => 'On Clinical Evaluation or Symptomatic Assessment',
                'is_active' => 1,
            ],
            [
                'name' => 'SpO2 at <=94%',
                'is_active' => 1,
            ],
            [
                'name' => 'Cough with Expectoration',
                'is_active' => 1,
            ],
            [
                'name' => 'Sputum (may be colored or blood stained)',
                'is_active' => 1,
            ],
            [
                'name' => 'Increased in respiration rate > 20 BPM',
                'is_active' => 1,
            ],
            [
                'name' => 'Shortness or Difficulty in Breathing',
                'is_active' => 1,
            ],
            [
                'name' => 'Pain or burning in the Chest',
                'is_active' => 1,
            ],
            [
                'name' => 'SpO2 < 94%',
                'is_active' => 1,
            ],
            [
                'name' => 'Respiratory Rate > 30 BPM',
                'is_active' => 1,
            ],
            [
                'name' => 'Imaging Lung infilltrates > 50%',
                'is_active' => 1,
            ],
            [
                'name' => 'Po2 / F1o2 (Less than 300mm Hg)',
                'is_active' => 1,
            ],
            [
                'name' => 'Respiratory Failure',
                'is_active' => 1,
            ],
            [
                'name' => 'Septic shock',
                'is_active' => 1,
            ],
            [
                'name' => 'Multiple Organ Dysfunction',
                'is_active' => 1,
            ],

        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $current_form_id = $forms["Triage (SOP) in Covid 19"];

        $form_questions = [
            ['question_id' => $questions["PRE / ASYMPTOMATIC"],'form_id' => $current_form_id],
            ['question_id' => $questions["MILD ILLNESS"],'form_id' => $current_form_id],
            ['question_id' => $questions["MODERATE ILLNESS"],'form_id' => $current_form_id],
            ['question_id' => $questions["SEVERE ILLNESS"],'form_id' => $current_form_id],
            ['question_id' => $questions["CRITICAL ILLNESS"],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        #1
        $current_question_id   = $questions['PRE / ASYMPTOMATIC'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["Test +ve(positive)"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["No Symptoms that are consistant with Covid19"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #2
        $current_question_id   = $questions['MILD ILLNESS'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["Symptoms of Fever, Cough, Soar Throat, Fatigue, Head ache, Body ache, loss of smell & or Taste etc"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["No shortness of breath"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["No difficulty in breathing"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["No abnormal chest imaging"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["No Current Mental Conditions"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Correlate with No co-morbidity if any"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #3
        $current_question_id   = $questions['MODERATE ILLNESS'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["Evidence of lower respiratory infections"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Positive Chest Imaging"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["On Clinical Evaluation or Symptomatic Assessment"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["SpO2 at <=94%"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Cough with Expectoration"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Sputum (may be colored or blood stained)"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Increased in respiration rate > 20 BPM"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Shortness or Difficulty in Breathing"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Pain or burning in the Chest"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #4
        $current_question_id   = $questions['SEVERE ILLNESS'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["SpO2 < 94%"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Respiratory Rate > 30 BPM"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Imaging Lung infilltrates > 50%"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Po2 / F1o2 (Less than 300mm Hg)"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #5
        $current_question_id   = $questions['CRITICAL ILLNESS'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["Respiratory Failure"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Septic shock"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Multiple Organ Dysfunction"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
