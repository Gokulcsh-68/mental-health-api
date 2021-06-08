<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamConductDisorder extends Seeder
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
                'name' => 'CONDUCT DISORDER', 
                'desc' => 'This clinician-rated severity measure is used for the assessment of the presence and severity of any CONDUCT DISORDER problems.', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-Conduct-Disorder', 
                'role_code' => json_encode(array("school", "provider")),
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'In the past seven (7) days.', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'None (No conduct problems)',
                'is_active' => 1,
            ],
            [
                'name' => 'Mild (Few if any conduct problems in excess of those required to make the diagnosis are present, and conduct problems cause relatively minor harm to others [e.g., lying, truancy, staying out after dark without permission, or other rule breaking])',
                'is_active' => 1,
            ],
            [
                'name' => 'Moderate (The number of conduct problems and the effect on others are intermediate between “mild” and “severe” [e.g., stealing without confronting a victim, vandalism])',
                'is_active' => 1,
            ],
            [
                'name' => 'Severe (Many conduct problems in excess of those required to make the diagnosis are present, or conduct problems cause considerable harm to others [e.g., forced sex, physical cruelty, use of a weapon, stealing while confronting a victim, breaking and entering])',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["CONDUCT DISORDER"];

        $form_questions = [
            ['question_id' => $questions['In the past seven (7) days.'],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['In the past seven (7) days.'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id,'type'=> $radio_type,'is_active' => 1,
             'name' => 'Rate the level or severity of the conduct problems that are present for this individual.'],
        ];

        DB::table('questions')->insert($new_sub_questions);

        #1
        $current_question_id    = $questions['In the past seven (7) days.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None (No conduct problems)"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild (Few if any conduct problems in excess of those required to make the diagnosis are present, and conduct problems cause relatively minor harm to others [e.g., lying, truancy, staying out after dark without permission, or other rule breaking])"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate (The number of conduct problems and the effect on others are intermediate between “mild” and “severe” [e.g., stealing without confronting a victim, vandalism])"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe (Many conduct problems in excess of those required to make the diagnosis are present, or conduct problems cause considerable harm to others [e.g., forced sex, physical cruelty, use of a weapon, stealing while confronting a victim, breaking and entering])"], 'jump_to_question_id' => null, 'score' => 3],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
