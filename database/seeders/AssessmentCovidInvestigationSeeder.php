<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssessmentCovidInvestigationSeeder extends Seeder
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
                'name' => 'Investigation for Biochemical Monitoring of Covid 19 Patients', 
                'desc' => 'Investigation for Biochemical Monitoring of Covid 19 Patients', 
                'assessment_group' => 'covid', 
                'type' => 'score', 
                'slug' => 'covid_investigation_biochemical', 
                'role_code' => '["admin", "provider", "school", "staff", "student"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);


        $insert_questions = [
            [
                'name' => 'WBC (Complete Blood Count)', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Neutrophil (Complete Blood Count)', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Lympocyte (Complete Blood Count)', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Platlet Count (Complete Blood Count)', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Blood Gases', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Albumin', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Lactate dehydrogenase (LDH)?', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'ALT (S.G.P.T)', 
                'type' => 'input',
                'is_active' => 1,
            ],
             [
                'name' => 'AST (S.G.O.T)', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Total Bilirubin', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Creatinine', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Urea', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Cardiac Troponin', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'D-Dimer', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Prothrombin Time', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Procalcitonin', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'C-reactive Protein', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Ferritin', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'Cytokines', 
                'type' => 'input',
                'is_active' => 1,
            ],
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_questions = [
            [
                'name' => 'enter in (cells/cmm)',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in (Lakh/cmm)',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in gm/dl',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in U/L',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in mg/dl',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in ng/ml',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in mcg/ml',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in seconds',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in mg/l',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in mu/l',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in pg/ml',
                'is_active' => 1,
            ]
        ];

        DB::table('answers')->insert($insert_questions);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $current_form_id = $forms["Investigation for Biochemical Monitoring of Covid 19 Patients"];

        $form_questions = [
            ['question_id' => $questions["WBC (Complete Blood Count)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Neutrophil (Complete Blood Count)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Lympocyte (Complete Blood Count)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Platlet Count (Complete Blood Count)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Blood Gases"],'form_id' => $current_form_id],
            ['question_id' => $questions["Albumin"],'form_id' => $current_form_id],
            ['question_id' => $questions["Lactate dehydrogenase (LDH)?"],'form_id' => $current_form_id],
            ['question_id' => $questions["ALT (S.G.P.T)"],'form_id' => $current_form_id],
            ['question_id' => $questions["AST (S.G.O.T)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Total Bilirubin"],'form_id' => $current_form_id],
            ['question_id' => $questions["Creatinine"],'form_id' => $current_form_id],
            ['question_id' => $questions["Urea"],'form_id' => $current_form_id],
            ['question_id' => $questions["Cardiac Troponin"],'form_id' => $current_form_id],
            ['question_id' => $questions["D-Dimer"],'form_id' => $current_form_id],
            ['question_id' => $questions["Prothrombin Time"],'form_id' => $current_form_id],
            ['question_id' => $questions["Procalcitonin"],'form_id' => $current_form_id],
            ['question_id' => $questions["C-reactive Protein"],'form_id' => $current_form_id],
            ['question_id' => $questions["Ferritin"],'form_id' => $current_form_id],
            ['question_id' => $questions["Cytokines"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

        #1
        $current_question_id   = $questions['WBC (Complete Blood Count)'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in (cells/cmm)"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #2
        $current_question_id   = $questions['Neutrophil (Complete Blood Count)'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in %"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #3
        $current_question_id   = $questions['Lympocyte (Complete Blood Count)'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in %"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #4
        $current_question_id   = $questions['Platlet Count (Complete Blood Count)'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in (Lakh/cmm)"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #5
        $current_question_id   = $questions['Blood Gases'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in %"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #6
        $current_question_id   = $questions['Albumin'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in gm/dl"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #7
        $current_question_id   = $questions['Lactate dehydrogenase (LDH)?'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in U/L"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


        #8
        $current_question_id   = $questions['ALT (S.G.P.T)'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in U/L"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #9
        $current_question_id   = $questions['AST (S.G.O.T)'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in U/L"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #10
        $current_question_id   = $questions['Total Bilirubin'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in mg/dl"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #11
        $current_question_id   = $questions['Creatinine'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in mg/dl"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #12
        $current_question_id   = $questions['Urea'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in mg/dl"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #13
        $current_question_id   = $questions['Cardiac Troponin'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in ng/ml"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


        #14
        $current_question_id   = $questions['D-Dimer'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in mcg/ml"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #15
        $current_question_id   = $questions['Prothrombin Time'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in seconds"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #16
        $current_question_id   = $questions['Procalcitonin'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in ng/ml"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #17
        $current_question_id   = $questions['C-reactive Protein'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in mg/l"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #18
        $current_question_id   = $questions['Ferritin'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in mu/l"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        #19
        $current_question_id   = $questions['Cytokines'];
        $form_question_answers = [
         ['question_id' => $current_question_id, 'answer_id' => $answers["enter in pg/ml"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


    }
}
