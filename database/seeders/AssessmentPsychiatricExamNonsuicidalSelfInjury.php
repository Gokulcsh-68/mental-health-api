<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamNonsuicidalSelfInjury extends Seeder
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
                'name' => 'NONSUICIDAL SELF-INJURY', 
                'desc' => 'This clinician-rated severity measure is used for the assessment of the presence and severity of any NONSUICIDAL SELF-INJURY (NSSI) behaviors or problems.', 
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
                'name' => 'In the past year.', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'None (No NSSI acts or NSSI acts on fewer than 3 days AND no urge to self- injure again.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Subthreshold (NSSI acts on 2-4 days OR has self- injured in the past on 5 or more days and has reported urges to self-injure again.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Mild (NSSI acts on 5–7 days using a single method and not requiring surgical treatment [other than cosmetic].)',
                'is_active' => 1,
            ],
            [
                'name' => 'Moderate (NSSI acts on 8–11 days using a single method and not requiring surgical treatment [other than cosmetic] OR NSSI acts on 5–7 days using more than one method.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Severe (At least 1 NSSI act that required surgical treatment [other than cosmetic] OR NSSI acts on 12 or more days using a single method OR NSSI acts on 8 or more days using more than one method.)',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["NONSUICIDAL SELF-INJURY"];

        $form_questions = [
            ['question_id' => $questions['In the past year.'],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['In the past year.'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id,'type'=> $radio_type,'is_active' => 1,
             'name' => 'Rate the level or severity of the NONSUICIDAL SELF-INJURY problems that are present for this individual.'],
        ];

        DB::table('questions')->insert($new_sub_questions);

        #1
        $current_question_id    = $questions['In the past year.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None (No NSSI acts or NSSI acts on fewer than 3 days AND no urge to self- injure again.)"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Subthreshold (NSSI acts on 2-4 days OR has self- injured in the past on 5 or more days and has reported urges to self-injure again.)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild (NSSI acts on 5–7 days using a single method and not requiring surgical treatment [other than cosmetic].)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate (NSSI acts on 8–11 days using a single method and not requiring surgical treatment [other than cosmetic] OR NSSI acts on 5–7 days using more than one method.)"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe (At least 1 NSSI act that required surgical treatment [other than cosmetic] OR NSSI acts on 12 or more days using a single method OR NSSI acts on 8 or more days using more than one method.)"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
