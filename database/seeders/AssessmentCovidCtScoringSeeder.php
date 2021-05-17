<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssessmentCovidCtScoringSeeder extends Seeder
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
                'name' => 'CT Imaging for COVID19', 
                'desc' => 'CT Imaging for COVID19', 
                'assessment_group' => 'covid', 
                'type' => 'score', 
                'slug' => 'covid_ct_scoring', 
                'role_code' => '["admin", "provider", "school", "staff", "student"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);


        $insert_questions = [
            [
                'name' => 'CT Scoring', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'CO-RADS - Level of Suspension COVID19 infection', 
                'type' => 'radio',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_questions = [
            [
                'name' => '0',
                'is_active' => 1,
            ],
            [
                'name' => '1 to 14',
                'is_active' => 1,
            ],
            [
                'name' => '15 to 25',
                'is_active' => 1,
            ],
            [
                'name' => '26 to 40',
                'is_active' => 1,
            ],
            [
                'name' => 'Not Interpretable',
                'is_active' => 1,
            ],
            [
                'name' => 'Very Low',
                'is_active' => 1,
            ],
            [
                'name' => 'Low',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal / Unsure',
                'is_active' => 1,
            ],
            [
                'name' => 'Very High',
                'is_active' => 1,
            ],
            [
                'name' => 'Proven',
                'is_active' => 1,
            ]
        ];

        DB::table('answers')->insert($insert_questions);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $current_form_id = $forms["CT Imaging for COVID19"];

        $form_questions = [
            ['question_id' => $questions["CT Scoring"],'form_id' => $current_form_id],
            ['question_id' => $questions["CO-RADS - Level of Suspension COVID19 infection"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

        #1
        $current_question_id   = $questions['CT Scoring'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["0"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["1 to 14"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["15 to 25"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["26 to 40"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #2
        $current_question_id   = $questions['CO-RADS - Level of Suspension COVID19 infection'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["Not Interpretable"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Very Low"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Low"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Equivocal / Unsure"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["High"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Very High"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 'answer_id' => $answers["Proven"], 'jump_to_question_id' => null],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
