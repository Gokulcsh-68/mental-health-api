<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssessmentVisonSymptomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assessmentGroups = [
    		[
                'master_type_slug' => 'assessment-group', 
                'slug' => 'vision', 
                'name' => 'Vision',
                'is_active' => 1,
            ]
    	];

        DB::table('masters')->insertOrIgnore($assessmentGroups);

        $forms = [
            [
                'name' => 'Vision Symptoms', 
                'desc' => 'Vision Symptoms', 
                'assessment_group' => 'vision', 
                'type' => 'score', 
                'slug' => 'vision_symptoms', 
                'role_code' => '["admin", "provider", "school", "staff", "student", "staffStudentAssement"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insertOrIgnore($forms);

        $insert_questions = [
            [
                'name' => 'As a teacher or parent are you concerned with this student’s vision?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Tilts head, squints, closes or covers one eye when reading?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Gaze issues, eyes turn in or out, crossed eyes, eyes wander', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Different size pupils or eyes', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Watery eyes, eyes appear hazy or clouded', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Words float, move, or jump around when reading', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Complains of headaches, dizziness, or nausea when reading (please specify)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Complains of itching, burning, or scratchy eyes (please specify)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Complains of blurred or double vision, unusual sensitivity to light, or difficulty seeing (please specify):', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'History of head injury with vision complaints', 
                'type' => 'radio',
                'is_active' => 1,
            ],
             [
                'name' => 'Loses place when reading', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Skips over or leaves out small words when reading', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Writes uphill or downhill; difficulty writing in a straight line', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Has difficulty copying from the board', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Avoids near work, such as reading or writing', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Has difficulty lining up numbers when doing math', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Has difficulty finishing assignments on time', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Holds books too close; leans too close to a computer screen', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Clumsy; bumps into things; knocks things over', 
                'type' => 'radio',
                'is_active' => 1,
            ]
        ];

        DB::table('questions')->insertOrIgnore($insert_questions);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $current_form_id = $forms["Vision Symptoms"];
        $form_questions = [
            ['question_id' => $questions["As a teacher or parent are you concerned with this student’s vision?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Tilts head, squints, closes or covers one eye when reading?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Gaze issues, eyes turn in or out, crossed eyes, eyes wander"],'form_id' => $current_form_id],
            ['question_id' => $questions["Different size pupils or eyes"],'form_id' => $current_form_id],
            ['question_id' => $questions["Watery eyes, eyes appear hazy or clouded"],'form_id' => $current_form_id],
            ['question_id' => $questions["Words float, move, or jump around when reading"],'form_id' => $current_form_id],
            ['question_id' => $questions["Complains of headaches, dizziness, or nausea when reading (please specify)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Complains of itching, burning, or scratchy eyes (please specify)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Complains of blurred or double vision, unusual sensitivity to light, or difficulty seeing (please specify):"],'form_id' => $current_form_id],
            ['question_id' => $questions["History of head injury with vision complaints"],'form_id' => $current_form_id],
            ['question_id' => $questions["Loses place when reading"],'form_id' => $current_form_id],
            ['question_id' => $questions["Skips over or leaves out small words when reading"],'form_id' => $current_form_id],
            ['question_id' => $questions["Writes uphill or downhill; difficulty writing in a straight line"],'form_id' => $current_form_id],
            ['question_id' => $questions["Has difficulty copying from the board"],'form_id' => $current_form_id],
            ['question_id' => $questions["Avoids near work, such as reading or writing"],'form_id' => $current_form_id],
            ['question_id' => $questions["Has difficulty lining up numbers when doing math"],'form_id' => $current_form_id],
            ['question_id' => $questions["Has difficulty finishing assignments on time"],'form_id' => $current_form_id],
            ['question_id' => $questions["Holds books too close; leans too close to a computer screen"],'form_id' => $current_form_id],
            ['question_id' => $questions["Clumsy; bumps into things; knocks things over"],'form_id' => $current_form_id]
        ];

        DB::table('form_questions')->insertOrIgnore($form_questions);

        $current_question_id    = $questions['As a teacher or parent are you concerned with this student’s vision?'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Tilts head, squints, closes or covers one eye when reading?'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Gaze issues, eyes turn in or out, crossed eyes, eyes wander'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Different size pupils or eyes'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Watery eyes, eyes appear hazy or clouded'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Words float, move, or jump around when reading'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Complains of headaches, dizziness, or nausea when reading (please specify)'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Complains of itching, burning, or scratchy eyes (please specify)'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Complains of blurred or double vision, unusual sensitivity to light, or difficulty seeing (please specify):'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['History of head injury with vision complaints'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Loses place when reading'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Skips over or leaves out small words when reading'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Writes uphill or downhill; difficulty writing in a straight line'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Has difficulty copying from the board'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Avoids near work, such as reading or writing'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Has difficulty lining up numbers when doing math'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Has difficulty finishing assignments on time'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);

         $current_question_id    = $questions['Holds books too close; leans too close to a computer screen'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);


         $current_question_id    = $questions['Clumsy; bumps into things; knocks things over'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insertOrIgnore($form_question_answers);


    }
}
