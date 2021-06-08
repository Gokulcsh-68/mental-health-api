<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamSomaticSymptomDisorder extends Seeder
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
                'name' => 'SOMATIC SYMPTOM DISORDER', 
                'desc' => 'Based on all the information you have on the individual receiving care and using your clinical judgment, please rate the presence and severity of the following symptoms as experienced by the individual in the past seven (7) days.', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-somatic-symptom-disorder', 
                'role_code' => json_encode(array("school", "provider")),
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'Does the individual have or show disproportionate and persistent concerns about the medical seriousness of his/her symptoms?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Does the individual have or show a high level of health-related anxiety?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Does the individual spend excessive time and energy devoted to these symptoms or health concerns?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'Very much',
                'is_active' => 1,
            ]
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["SOMATIC SYMPTOM DISORDER"];

        $form_questions = [
            ['question_id' => $questions['Does the individual have or show disproportionate and persistent concerns about the medical seriousness of his/her symptoms?'],'form_id' => $current_form_id],
            ['question_id' => $questions['Does the individual have or show a high level of health-related anxiety?'],'form_id' => $current_form_id],
            ['question_id' => $questions['Does the individual spend excessive time and energy devoted to these symptoms or health concerns?'],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

        #1
        $current_question_id    = $questions['Does the individual have or show disproportionate and persistent concerns about the medical seriousness of his/her symptoms?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not at all"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["A little bit"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Somewhat"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Quite a bit"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Very much"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #2
        $current_question_id    = $questions['Does the individual have or show a high level of health-related anxiety?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not at all"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["A little bit"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Somewhat"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Quite a bit"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Very much"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #3
        $current_question_id    = $questions['Does the individual spend excessive time and energy devoted to these symptoms or health concerns?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not at all"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["A little bit"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Somewhat"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Quite a bit"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Very much"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
