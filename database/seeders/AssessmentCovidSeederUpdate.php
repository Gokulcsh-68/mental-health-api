<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssessmentCovidSeederUpdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $insert_questions = [
            [
                'name' => 'What is your Respiration Rate?',
                'type' => 'input',
                'is_active' => 1,
            ]
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_questions = [
            [
                'name' => 'Unit: Per minute',
                'is_active' => 1,
            ]
        ];

        DB::table('answers')->insert($insert_questions);


        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $current_form_id = $forms["Covid self assessment"];

        $form_questions = [
            ['question_id' => $questions["What is your Respiration Rate?"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

        $current_question_id   = $questions['What is your Respiration Rate?'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Unit: Per minute"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
