<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamPsychosisSymptomSeverity extends Seeder
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
                'name' => 'Dimensions of Psychosis Symptom Severity', 
                'desc' => 'Dimensions of Psychosis Symptom Severity', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-Psychosis-Symptom-Severity', 
                'role_code' => json_encode(array("school", "provider")),
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            ['name' => 'Hallucinations','type' => 'radio','is_active' => 1],
            ['name' => 'Delusions','type' => 'radio','is_active' => 1],
            ['name' => 'Disorganized speech','type' => 'radio','is_active' => 1],
            ['name' => 'Abnormal psychomotor behavior','type' => 'radio','is_active' => 1],
            ['name' => 'Negative symptoms (restricted emotional expression or avolition)',
                'type' => 'radio','is_active' => 1],
            ['name' => 'Impaired cognition','type' => 'radio','is_active' => 1],
            ['name' => 'Depression','type' => 'radio','is_active' => 1],
            ['name' => 'Mania','type' => 'radio','is_active' => 1]
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'Not present',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal (severity or duration not sufficient to be considered psychosis)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (little pressure to act upon voices, not very bothered by voices)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (some pressure to respond to voices, or is somewhat bothered by voices)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (severe pressure to respond to voices, or is very bothered by voices)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (little pressure to act upon delusional beliefs, not very bothered by beliefs)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (some pressure to act upon beliefs, or is somewhat bothered by beliefs)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (severe pressure to act upon beliefs, or is very bothered by beliefs)',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal (severity or duration not sufficient to be considered disorganization)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (some difficulty following speech)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (speech often difficult to follow)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (speech almost impossible to follow)',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal (severity or duration not sufficient to be considered abnormal psychomotor behavior)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (occasional abnormal or bizarre motor behavior or catatonia)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (frequent abnormal or bizarre motor behavior or catatonia)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (abnormal or bizarre motor behavior or catatonia almost constant)',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal decrease in facial expressivity, prosody, gestures, or self-initiated behavior',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild decrease in facial expressivity, prosody, gestures, or self-initiated behavior',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate decrease in facial expressivity, prosody, gestures, or self-initiated behavior',
                'is_active' => 1,
            ],

            [
                'name' => 'Present and severe decrease in facial expressivity, prosody, gestures, or self-initiated behavior',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal (cognitive function not clearly outside the range expected for age or SES; i.e., within 0.5 SD of mean)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (some reduction in cognitive function; below expected for age and SES, 0.5–1 SD from mean)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (clear reduction in cognitive function; below expected for age and SES, 1–2 SD from mean)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (severe reduction in cognitive function; below expected for age and SES, > 2 SD from mean)',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal (occasionally feels sad, down, depressed, or hopeless; concerned about having failed someone or at something but not preoccupied)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (frequent periods of feeling very sad, down, moderately depressed, or hopeless; concerned about having failed someone or at something, with some preoccupation)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (frequent periods of deep depression or hopelessness; preoccupation with guilt, having done wrong)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (deeply depressed or hopeless daily; delusional guilt or unreasonable self-reproach grossly out of proportion to circumstances)',
                'is_active' => 1,
            ],
            [
                'name' => 'Equivocal (occasional elevated, expansive, or irritable mood or some restlessness)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present, but mild (frequent periods of somewhat elevated, expansive, or irritable mood or restlessness)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and moderate (frequent periods of extensively elevated, expansive, or irritable mood or restlessness)',
                'is_active' => 1,
            ],
            [
                'name' => 'Present and severe (daily and extensively elevated, expansive, or irritable mood or restlessness)',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["Dimensions of Psychosis Symptom Severity"];
        $form_questions = [
            ['question_id' => $questions["Hallucinations"],'form_id' => $current_form_id],
            ['question_id' => $questions["Delusions"],'form_id' => $current_form_id],
            ['question_id' => $questions["Disorganized speech"],'form_id' => $current_form_id],
            ['question_id' => $questions["Abnormal psychomotor behavior"],'form_id' => $current_form_id],
            ['question_id' => $questions["Negative symptoms (restricted emotional expression or avolition)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Impaired cognition"],'form_id' => $current_form_id],
            ['question_id' => $questions["Depression"],'form_id' => $current_form_id],
            ['question_id' => $questions["Mania"],'form_id' => $current_form_id],
        ];


        DB::table('form_questions')->insert($form_questions);

        #1
        $current_question_id    = $questions['Hallucinations'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (severity or duration not sufficient to be considered psychosis)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (little pressure to act upon voices, not very bothered by voices)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (some pressure to respond to voices, or is somewhat bothered by voices)"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (severe pressure to respond to voices, or is very bothered by voices)"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #2
        $current_question_id    = $questions['Delusions'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (severity or duration not sufficient to be considered psychosis)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (little pressure to act upon delusional beliefs, not very bothered by beliefs)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (some pressure to act upon beliefs, or is somewhat bothered by beliefs)"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (severe pressure to act upon beliefs, or is very bothered by beliefs)"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #3
        $current_question_id    = $questions['Disorganized speech'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (severity or duration not sufficient to be considered disorganization)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (some difficulty following speech)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (speech often difficult to follow)"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (speech almost impossible to follow)"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #4
        $current_question_id    = $questions['Abnormal psychomotor behavior'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (severity or duration not sufficient to be considered abnormal psychomotor behavior)"],
             'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (occasional abnormal or bizarre motor behavior or catatonia)"],
             'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (frequent abnormal or bizarre motor behavior or catatonia)"],
             'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (abnormal or bizarre motor behavior or catatonia almost constant)"],
             'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #5
        $current_question_id    = $questions['Negative symptoms (restricted emotional expression or avolition)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal decrease in facial expressivity, prosody, gestures, or self-initiated behavior"],
             'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild decrease in facial expressivity, prosody, gestures, or self-initiated behavior"],
             'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate decrease in facial expressivity, prosody, gestures, or self-initiated behavior"],
             'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe decrease in facial expressivity, prosody, gestures, or self-initiated behavior"],
             'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #6
        $current_question_id    = $questions['Impaired cognition'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (cognitive function not clearly outside the range expected for age or SES; i.e., within 0.5 SD of mean)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (some reduction in cognitive function; below expected for age and SES, 0.5–1 SD from mean)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (clear reduction in cognitive function; below expected for age and SES, 1–2 SD from mean)"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (severe reduction in cognitive function; below expected for age and SES, > 2 SD from mean)"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #7
        $current_question_id    = $questions['Depression'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (occasionally feels sad, down, depressed, or hopeless; concerned about having failed someone or at something but not preoccupied)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (frequent periods of feeling very sad, down, moderately depressed, or hopeless; concerned about having failed someone or at something, with some preoccupation)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (frequent periods of deep depression or hopelessness; preoccupation with guilt, having done wrong)"], 'jump_to_question_id' => null, 'score' => 3],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (deeply depressed or hopeless daily; delusional guilt or unreasonable self-reproach grossly out of proportion to circumstances)"], 'jump_to_question_id' => null, 'score' => 4],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #8
        $current_question_id    = $questions['Mania'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Not present"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Equivocal (occasional elevated, expansive, or irritable mood or some restlessness)"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present, but mild (frequent periods of somewhat elevated, expansive, or irritable mood or restlessness)"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and moderate (frequent periods of extensively elevated, expansive, or irritable mood or restlessness)"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Present and severe (daily and extensively elevated, expansive, or irritable mood or restlessness)"], 'jump_to_question_id' => null, 'score' => 0],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
