<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentPsychiatricExamAutismSpectrumDisorder extends Seeder
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
                'name' => 'Autism Spectrum Disorder', 
                'desc' => 'Autism Spectrum Disorder', 
                'assessment_group' => 'psychiatric-exam', 
                'type' => 'score', 
                'slug' => 'psychiatric-exam-autism-spectrum-disorder', 
                'role_code' => json_encode(array("school", "provider")),
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'Rate the level of interference in functioning and support required as a result of RESTRICTED INTERESTS and REPETITIVE BEHAVIORS for this individual.', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
            [
                'name' => 'Mild Requiring support (i.e., Rituals and repetitive behaviors [RRBs] cause significant interference with functioning in one or more contexts. Resists attempts by others to interrupt RRBs or to be redirected from fixated interest.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Moderate Requiring SUBSTANTIAL support (i.e., RRBs and/or preoccupations and/or fixated interests appear frequently enough to be obvious to the casual observer and interfere with functioning in a variety of contexts. Distress or frustration is apparent when RRBs are interrupted; difficult to redirect from fixated interest.)',
                'is_active' => 1,
            ],
            [
                'name' => 'Severe Requiring VERY SUBSTANTIAL support (i.e., Preoccupations, fixed rituals and/or repetitive behaviors markedly interfere with functioning in all spheres. Marked distress when rituals or routines are interrupted; very difficult to redirect from fixated interest or returns to it quickly.)',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_answers);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $radio_type = 'radio';

        $current_form_id = $forms["Autism Spectrum Disorder"];

        $form_questions = [
            ['question_id' => $questions['Rate the level of interference in functioning and support required as a result of RESTRICTED INTERESTS and REPETITIVE BEHAVIORS for this individual.'],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1 Sub question
        $current_question_id = $questions['Rate the level of interference in functioning and support required as a result of RESTRICTED INTERESTS and REPETITIVE BEHAVIORS for this individual.'];

        $new_sub_questions = [
            ['parent_id' => $current_question_id,'type'=> $radio_type,'is_active' => 1,
             'name' => 'Rate the level or severity of the Autism Spectrum Disorder problems that are present for this individual.'],
        ];

        DB::table('questions')->insert($new_sub_questions);

        #1
        $current_question_id    = $questions['Rate the level of interference in functioning and support required as a result of RESTRICTED INTERESTS and REPETITIVE BEHAVIORS for this individual.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild Requiring support (i.e., Rituals and repetitive behaviors [RRBs] cause significant interference with functioning in one or more contexts. Resists attempts by others to interrupt RRBs or to be redirected from fixated interest.)"], 'jump_to_question_id' => null, 'score' => 1],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate Requiring SUBSTANTIAL support (i.e., RRBs and/or preoccupations and/or fixated interests appear frequently enough to be obvious to the casual observer and interfere with functioning in a variety of contexts. Distress or frustration is apparent when RRBs are interrupted; difficult to redirect from fixated interest.)"], 'jump_to_question_id' => null, 'score' => 2],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe Requiring VERY SUBSTANTIAL support (i.e., Preoccupations, fixed rituals and/or repetitive behaviors markedly interfere with functioning in all spheres. Marked distress when rituals or routines are interrupted; very difficult to redirect from fixated interest or returns to it quickly.)"], 'jump_to_question_id' => null, 'score' => 3],
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
