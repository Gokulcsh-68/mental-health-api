<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamSocialCommunicationDisorder extends Seeder
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
                'name' => 'Social Communication Disorder', 
                'desc' => 'Social Communication Disorder', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-social-communication-disorder', 
                'role_code' => json_encode(array("school", "provider")),
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'Rate the level of interference in functioning and support required as a result of SOCIAL COMMUNICATION deficits for this individual.', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'Mild Requiring support (i.e., Without supports in place, deficits in social communication cause noticeable impairments. Has difficulty initiating social interactions and demonstrates clear examples of atypical or unsuccessful responses to social overtures of others. May appear to have decreased interest in social interactions.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Moderate Requiring SUBSTANTIAL support (i.e., Marked deficits in verbal and nonverbal social communication skills; social impairments apparent even with supports in place; limited initiation of social interactions and reduced or abnormal response to social overtures from others.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Severe Requiring VERY SUBSTANTIAL support (i.e., Severe deficits in verbal and nonverbal social communication skills cause severe impairments in functioning; very limited initiation of social interactions and minimal response to social overtures from others.)',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["Social Communication Disorder"];

        $form_questions = [
            ['question_id' => $questions['Rate the level of interference in functioning and support required as a result of SOCIAL COMMUNICATION deficits for this individual.'],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['Rate the level of interference in functioning and support required as a result of SOCIAL COMMUNICATION deficits for this individual.'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id,'type'=> $radio_type,'is_active' => 1,
             'name' => 'Rate the level or severity of the Social Communication Disorder problems that are present for this individual.'],
        ];

        DB::table('questions')->insert($new_sub_questions);

        #1
        $current_question_id    = $questions['Rate the level of interference in functioning and support required as a result of SOCIAL COMMUNICATION deficits for this individual.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild Requiring support (i.e., Without supports in place, deficits in social communication cause noticeable impairments. Has difficulty initiating social interactions and demonstrates clear examples of atypical or unsuccessful responses to social overtures of others. May appear to have decreased interest in social interactions.)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate Requiring SUBSTANTIAL support (i.e., Marked deficits in verbal and nonverbal social communication skills; social impairments apparent even with supports in place; limited initiation of social interactions and reduced or abnormal response to social overtures from others.)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe Requiring VERY SUBSTANTIAL support (i.e., Severe deficits in verbal and nonverbal social communication skills cause severe impairments in functioning; very limited initiation of social interactions and minimal response to social overtures from others.)"], 'jump_to_question_id' => null, 'score' => 3],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
