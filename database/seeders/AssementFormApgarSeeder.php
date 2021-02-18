<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssementFormApgarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $radio_type     = 'radio';

    	$assessmentGroups = [
    		[
                'master_type_slug' => 'assessment-group', 
                'slug' => str_slug('APGAR'), 
                'name' => 'APGAR',
                'is_active' => 1,
            ]
    	];

       DB::table('masters')->insert($assessmentGroups);

    	$forms = [
    		[
                'name' => 'Apgar Scoring System', 
                'desc' => 'Apgar Scoring System', 
                'assessment_group' => str_slug('APGAR'), 
                'type' => 'score',
                'slug' => str_slug('Apgar Scoring System'),
                'is_active' => 1,
                'role_code' => json_encode(array("school","student"))
            ]
    	];

        DB::table('forms')->insert($forms);


        $insert_questions = [
            [
                'name' => 'Activity (muscle tone)',
                'type' => $radio_type,
                'is_active' => 1,
            ],
            [
                'name' => 'Pulse',
                'type' => $radio_type,
                'is_active' => 1,
            ],
            [
                'name' => 'Grimace (reflex irritability)',
                'type' => $radio_type,
                'is_active' => 1,
            ],
            [
                'name' => 'Appearance (skin color)',
                'type' => $radio_type,
                'is_active' => 1,
            ],
            [
                'name' => 'Respiration',
                'type' => $radio_type,
                'is_active' => 1,
            ]
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
    		[
                'name' => 'Absent',
                'is_active' => 1,
            ],
            [
                'name' => 'Floppy',
                'is_active' => 1,
            ],
            [
                'name' => 'Blue; Pale',
                'is_active' => 1,
            ],
            [
                'name' => 'Flex arms and legs',
                'is_active' => 1,
            ],
            [
                'name' => 'Below 100 bpm',
                'is_active' => 1,
            ],
            [
                'name' => 'Minimal response to stimulation',
                'is_active' => 1,
            ],
            [
                'name' => 'Pink body, Blue Extremities',
                'is_active' => 1,
            ],
            [
                'name' => 'Slow and irregular',
                'is_active' => 1,
            ],
            [
                'name' => 'Active',
                'is_active' => 1,
            ],
            [
                'name' => 'Over 100 bpm',
                'is_active' => 1,
            ],
            [
                'name' => 'Prompt response to stimulation',
                'is_active' => 1,
            ],
            [
                'name' => 'Pink',
                'is_active' => 1,
            ],
            [
                'name' => 'Vigorous cry',
                'is_active' => 1,
            ]
    	];

        DB::table('answers')->insert($insert_answers);


    	$questions 		= json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$answers 		= json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$forms 			= json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		

        $current_form_id = $forms["Apgar Scoring System"];
        $form_questions = [
            ['question_id' => $questions["Activity (muscle tone)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Pulse"],'form_id' => $current_form_id],
            ['question_id' => $questions["Grimace (reflex irritability)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Appearance (skin color)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Respiration"],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

		$current_question_id 	= $questions['Activity (muscle tone)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Absent"], 'jump_to_question_id' => null, 'score' => 0],
			['question_id' => $current_question_id, 'answer_id' => $answers["Flex arms and legs"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Active"], 'jump_to_question_id' => null, 'score' => 2]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

        $current_question_id    = $questions['Pulse'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 'answer_id' => $answers["Absent"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Below 100 bpm"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Over 100 bpm"], 'jump_to_question_id' => null, 'score' => 2]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        $current_question_id    = $questions['Grimace (reflex irritability)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 'answer_id' => $answers["Floppy"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Minimal response to stimulation"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Prompt response to stimulation"], 'jump_to_question_id' => null, 'score' => 2]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        $current_question_id    = $questions['Appearance (skin color)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 'answer_id' => $answers["Blue; Pale"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Pink body, Blue Extremities"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Pink"], 'jump_to_question_id' => null, 'score' => 2]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        $current_question_id    = $questions['Respiration'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 'answer_id' => $answers["Absent"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Slow and irregular"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Vigorous cry"], 'jump_to_question_id' => null, 'score' => 2]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

    }
}
