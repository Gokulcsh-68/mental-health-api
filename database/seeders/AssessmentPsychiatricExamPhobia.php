<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamPhobia extends Seeder
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
                'name' => 'Phobia', 
                'desc' => 'Phobia', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-Phobia', 
                'role_code' => '["admin", "provider", "school", "staff", "student"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'Choose only one item and make your ratings based on the situations included in that item.', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'During the PAST 7 DAYS, I have...', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'Driving, flying, tunnels, bridges, or enclosed spaces',
                'is_active' => 1,
            ],
            [
                'name' => 'Animals or insects',
                'is_active' => 1,
            ],
            [
                'name' => 'Heights, storms, or water',
                'is_active' => 1,
            ],
            [
                'name' => 'Blood, needles, or injections',
                'is_active' => 1,
            ],
            [
                'name' => 'Choking or vomiting',
                'is_active' => 1,
            ],
            [
                'name' => 'Half of the time',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["Phobia"];

        $form_questions = [
            ['question_id' => $questions['Choose only one item and make your ratings based on the situations included in that item.'],'form_id' => $current_form_id],
            ['question_id' => $questions['During the PAST 7 DAYS, I have...'],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['During the PAST 7 DAYS, I have...'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 
                        'felt moments of sudden terror, fear, or fright in these situations','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'felt anxious, worried, or nervous about these situations','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'had thoughts of being injured, overcome with fear, or other bad things happening in these situations',
                'type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'felt a racing heart, sweaty, trouble breathing, faint, or shaky in these situations',
                'type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'felt tense muscles, felt on edge or restless, or had trouble relaxing in these situations',
                'type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'avoided, or did not approach or enter, these situations','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'moved away from these situations or left them early','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'spent a lot of time preparing for, or procrastinating about (i.e., putting off), these situations',
                    'type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'distracted myself to avoid thinking about these situations',
                    'type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 
                        'needed help to cope with these situations (e.g., alcohol or medications, superstitious objects, other people)',
                    'type'=> $radio_type,'is_active' => 1],
        ];

        DB::table('questions')->insert($new_sub_questions);

        #1
        $current_question_id    = $questions['Choose only one item and make your ratings based on the situations included in that item.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Driving, flying, tunnels, bridges, or enclosed spaces"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Animals or insects"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Heights, storms, or water"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Blood, needles, or injections"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Choking or vomiting"], 'jump_to_question_id' => null],
        ];

         DB::table('form_question_answers')->insert($form_question_answers);

        #2
        $current_question_id    = $questions['During the PAST 7 DAYS, I have...'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Occasionally"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Half of the time"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Most of the Time"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["All of the Time"], 'jump_to_question_id' => null, 'score' => 4]
        ];

         DB::table('form_question_answers')->insert($form_question_answers);


    }
}
