<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamOppositionalDefiantDisorder extends Seeder
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
                'name' => 'OPPOSITIONAL DEFIANT DISORDER', 
                'desc' => 'This clinician-rated severity measure is used for the assessment of the presence and severity of any OPPOSITIONAL DEFIANT DISORDER symptoms.', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-oppositional-defiant-disorder', 
                'role_code' => json_encode(array("school", "provider")),
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'Oppositional defiant disorder in the past year.', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'None (No oppositional defiant symptoms)',
                'is_active' => 1,
            ],
            [
                'name' => 'Mild (Symptoms are confined to only one setting [e.g., at home, at school, at work, with peers])',
                'is_active' => 1,
            ],
            [
                'name' => 'Moderate (Some symptoms are present in at least two settings)',
                'is_active' => 1,
            ],
            [
                'name' => 'Severe (Some symptoms are present in three or more settings)',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["OPPOSITIONAL DEFIANT DISORDER"];

        $form_questions = [
            ['question_id' => $questions['Oppositional defiant disorder in the past year.'],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['Oppositional defiant disorder in the past year.'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id,'type'=> $radio_type,'is_active' => 1,
             'name' => 'Rate the level or severity of the OPPOSITIONAL DEFIANT problems that are present for this individual.'],
        ];

        DB::table('questions')->insert($new_sub_questions);

        #1
        $current_question_id    = $questions['Oppositional defiant disorder in the past year.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None (No oppositional defiant symptoms)"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild (Symptoms are confined to only one setting [e.g., at home, at school, at work, with peers])"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate (Some symptoms are present in at least two settings)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe (Some symptoms are present in three or more settings)"], 'jump_to_question_id' => null, 'score' => 3],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
