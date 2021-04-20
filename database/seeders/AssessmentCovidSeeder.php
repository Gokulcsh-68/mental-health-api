<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssessmentCovidSeeder extends Seeder
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
                'slug' => 'covid', 
                'name' => 'Covid',
                'is_active' => 1,
            ]
    	];

        DB::table('masters')->insert($assessmentGroups);

        $forms = [
            [
                'name' => 'Covid self assessment', 
                'desc' => 'Covid self assessment', 
                'assessment_group' => 'covid', 
                'type' => 'score', 
                'slug' => 'covid_self_assessment', 
                'role_code' => '["admin", "provider", "school", "staff", "student"]',
                'is_active' => 1,
            ]
        ];

        DB::table('forms')->insert($forms);

        $insert_questions = [
            [
                'name' => 'Are you feeling Fever or chills?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is the severity of your headache? (If you have a severe headache that started suddenly and is the worst headache of your life)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is the severity of your body aches? (Such as unusual muscle aching through your body)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Are you Feeling confused & Losing consciousness?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Do you have Extreme fatigue or tiredness?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
             [
                'name' => 'Do you have Pink eye? (In case of pink eye, people can develop redness, swelling and watery eyes)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
             [
                'name' => 'Are you feeling Rhinorrhea and / or Nasal Congestion?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
             [
                'name' => 'Are you experiencing any of the following:
1.Mild to moderate shortness of breath
2.Inability to lie down because of difficulty breathing
3.Chronic health conditions that you are having difficulty managing because of difficulty breathing', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is the severity of your cough ?
                (Such as a dry, persistent cough, or a cough with sputum. If your cough is getting worse and you are coughing up blood or sputum, or if it is causing shortness of breath)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Do you have Loss of sense of smell or taste?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is the severity of your sore throat ?
                (If your pain is increasing or if you are having trouble swallowing, please talk to your doctor or nurse practitioner)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Are you feeling or having a fast-beating, fluttering and pounding heart or Chest Pain or Pressure ? (60 per cent had ongoing myocardial inflammation)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Do you feel Loss of appetite?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is the severity of your nausea or vomiting or  abdominal cramps?
                If you are not able to keep down even small sips of water or feel like you are becoming dehydrated (such as less frequent urination, or becoming light-headed when standing)', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is the severity of your diarrhea?
                Such as loose or watery stool that is not normal for you.If your diarrhea is getting worse, if you have bloody diarrhea or black, tarry stool, or if you feel like you are becoming dehydrated (such as less frequent urination, or becoming light-headed when standing', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Are you having any of these problems?', 
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Are these above mentioned symptoms unusual for you AND have they lasted more than 24hrs?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Have you received a new COVID-19 test result? (Optional) If you have not entered it here before, please enter the details of your last COVID-19 test.', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Have you been out country (including the Canada, United States) within the last 14 days?', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'Did you have close contact with a person with confirmed COVID-19 within the last 14 days?
                A close contact is someone confirmed to have COVID-19 who you live with or otherwise had close face to face contact (within 2 metres) while they had symptoms or in the 48 hours before their symptoms started.', 
                'type' => 'radio',
                'is_active' => 1,
            ],
            [
                'name' => 'What is your present Temperature value in fahrenheit?', 
                'type' => 'input',
                'is_active' => 1,
            ],
            [
                'name' => 'What is your present SpO2 value?',
                'type' => 'input',
                'is_active' => 1,
            ]
        ];

        DB::table('questions')->insert($insert_questions);

        $insert_questions = [
            [
                'name' => 'Enter fahrenheit value',
                'is_active' => 1,
            ],
            [
                'name' => 'enter in %',
                'is_active' => 1,
            ],
        ];

        DB::table('answers')->insert($insert_questions);

        $questions  = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $answers    = json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);
        $forms      = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE), true);

        $current_form_id = $forms["Covid self assessment"];
        $form_questions = [
            ['question_id' => $questions["Are you feeling Fever or chills?"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is the severity of your headache? (If you have a severe headache that started suddenly and is the worst headache of your life)"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is the severity of your body aches? (Such as unusual muscle aching through your body)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Are you Feeling confused & Losing consciousness?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Do you have Extreme fatigue or tiredness?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Do you have Pink eye? (In case of pink eye, people can develop redness, swelling and watery eyes)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Are you feeling Rhinorrhea and / or Nasal Congestion?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Are you experiencing any of the following:
1.Mild to moderate shortness of breath
2.Inability to lie down because of difficulty breathing
3.Chronic health conditions that you are having difficulty managing because of difficulty breathing"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is the severity of your cough ?
                (Such as a dry, persistent cough, or a cough with sputum. If your cough is getting worse and you are coughing up blood or sputum, or if it is causing shortness of breath)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Do you have Loss of sense of smell or taste?"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is the severity of your sore throat ?
                (If your pain is increasing or if you are having trouble swallowing, please talk to your doctor or nurse practitioner)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Are you feeling or having a fast-beating, fluttering and pounding heart or Chest Pain or Pressure ? (60 per cent had ongoing myocardial inflammation)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Do you feel Loss of appetite?"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is the severity of your nausea or vomiting or  abdominal cramps?
                If you are not able to keep down even small sips of water or feel like you are becoming dehydrated (such as less frequent urination, or becoming light-headed when standing)"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is the severity of your diarrhea?
                Such as loose or watery stool that is not normal for you.If your diarrhea is getting worse, if you have bloody diarrhea or black, tarry stool, or if you feel like you are becoming dehydrated (such as less frequent urination, or becoming light-headed when standing"],'form_id' => $current_form_id],
            ['question_id' => $questions["Are you having any of these problems?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Are these above mentioned symptoms unusual for you AND have they lasted more than 24hrs?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Have you received a new COVID-19 test result? (Optional) If you have not entered it here before, please enter the details of your last COVID-19 test."],'form_id' => $current_form_id],
            ['question_id' => $questions["Have you been out country (including the Canada, United States) within the last 14 days?"],'form_id' => $current_form_id],
            ['question_id' => $questions["Did you have close contact with a person with confirmed COVID-19 within the last 14 days?
                A close contact is someone confirmed to have COVID-19 who you live with or otherwise had close face to face contact (within 2 metres) while they had symptoms or in the 48 hours before their symptoms started."],'form_id' => $current_form_id],
            ['question_id' => $questions["What is your present Temperature value in fahrenheit?"],'form_id' => $current_form_id],
            ['question_id' => $questions["What is your present SpO2 value?"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

        // 1
        $current_question_id    = $questions['Are you feeling Fever or chills?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 2
        $current_question_id    = $questions['What is the severity of your headache? (If you have a severe headache that started suddenly and is the worst headache of your life)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 3
        $current_question_id    = $questions['What is the severity of your body aches? (Such as unusual muscle aching through your body)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 4
        $current_question_id    = $questions['Are you Feeling confused & Losing consciousness?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 5
        $current_question_id    = $questions['Do you have Extreme fatigue or tiredness?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 6
        $current_question_id    = $questions['Do you have Pink eye? (In case of pink eye, people can develop redness, swelling and watery eyes)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 7
        $current_question_id    = $questions['Are you feeling Rhinorrhea and / or Nasal Congestion?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 8
        $current_question_id    = $questions['Are you experiencing any of the following:
1.Mild to moderate shortness of breath
2.Inability to lie down because of difficulty breathing
3.Chronic health conditions that you are having difficulty managing because of difficulty breathing'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 9
        $current_question_id    = $questions['What is the severity of your cough ?
                (Such as a dry, persistent cough, or a cough with sputum. If your cough is getting worse and you are coughing up blood or sputum, or if it is causing shortness of breath)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 10
        $current_question_id    = $questions['Do you have Loss of sense of smell or taste?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 11
        $current_question_id    = $questions['What is the severity of your sore throat ?
                (If your pain is increasing or if you are having trouble swallowing, please talk to your doctor or nurse practitioner)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 12
        $current_question_id    = $questions['Are you feeling or having a fast-beating, fluttering and pounding heart or Chest Pain or Pressure ? (60 per cent had ongoing myocardial inflammation)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 13
        $current_question_id    = $questions['Do you feel Loss of appetite?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 14
        $current_question_id    = $questions['What is the severity of your nausea or vomiting or  abdominal cramps?
                If you are not able to keep down even small sips of water or feel like you are becoming dehydrated (such as less frequent urination, or becoming light-headed when standing)'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 15
        $current_question_id    = $questions['What is the severity of your diarrhea?
                Such as loose or watery stool that is not normal for you.If your diarrhea is getting worse, if you have bloody diarrhea or black, tarry stool, or if you feel like you are becoming dehydrated (such as less frequent urination, or becoming light-headed when standing'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["None"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Mild"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Severe"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 16
        # Are you having any of these problems? Sub Question
        $current_question_id = $questions['Are you having any of these problems?'];
        $radio_type          = 'radio';

        $new_sub_questions = [
            ['parent_id' => $current_question_id, 'name' => 'Cancer','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Cerebrovascular disease','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Chronic kidney disease','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Chronic obstructive pulmonary disease (COPD) and other lung disease (including interstitial lung disease, pulmonary fibrosis, pulmonary hypertension)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Diabetes mellitus, type 1 and type 2','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Down syndrome','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Heart conditions (such as heart failure, coronary artery disease, or cardiomyopathies)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'HIV','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Neurologic conditions, including dementia','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Overweight and obesity (BMI ≥25 kg/m2)','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Pregnancy','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Sickle cell disease','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Smoking, current and former','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Solid organ or blood stem cell transplantation','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Substance use disorders','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Use of corticosteroids or other immunosuppressive medications','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Cystic fibrosis','type'=> $radio_type,'is_active' => 1],
            ['parent_id' => $current_question_id, 'name' => 'Thalassemia','type'=> $radio_type,'is_active' => 1]
        ];

        DB::table('questions')->insert($new_sub_questions);

        $current_question_id    = $questions['Are you having any of these problems?'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);

        // 17
        $current_question_id    = $questions['Are these above mentioned symptoms unusual for you AND have they lasted more than 24hrs?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 18
        $current_question_id = $questions["Have you received a new COVID-19 test result? (Optional) If you have not entered it here before, please enter the details of your last COVID-19 test."];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 19
        $current_question_id    = $questions['Have you been out country (including the Canada, United States) within the last 14 days?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

        // 20
        $current_question_id    = $questions['Did you have close contact with a person with confirmed COVID-19 within the last 14 days?
                A close contact is someone confirmed to have COVID-19 who you live with or otherwise had close face to face contact (within 2 metres) while they had symptoms or in the 48 hours before their symptoms started.'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
            ['question_id' => $current_question_id, 
            'answer_id' => $answers["No"], 'jump_to_question_id' => null]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);


        $current_question_id    = $questions['What is your present Temperature value in fahrenheit?'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["Enter fahrenheit value"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);


         $current_question_id   = $questions['What is your present SpO2 value?'];
         $form_question_answers = [
         ['question_id' => $current_question_id, 
            'answer_id' => $answers["enter in %"], 'jump_to_question_id' => null]
            ];

         DB::table('form_question_answers')->insert($form_question_answers);


    }
}
