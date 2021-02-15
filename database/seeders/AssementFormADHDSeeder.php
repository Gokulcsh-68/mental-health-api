<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssementFormADHDSeeder extends Seeder
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
                'slug' => str_slug('ADHD'), 
                'name' => 'ADHD',
                'is_active' => 1,
            ]
    	];

       DB::table('masters')->insert($assessmentGroups);

    	$forms = [
    		[
                'name' => 'Assessment Scale - Parent Informant', 
                'desc' => 'Assessment Scale - Parent Informant', 
                'assessment_group' => str_slug('ADHD'), 
                'type' => 'score',
                'slug' => str_slug('Assessment Scale - Parent Informant'),
                'is_active' => 1,
                'role_code' => json_encode(array("school", "student"))
            ],
            [
                'name' => 'Assessment Scale - Teacher Informant', 
                'desc' => 'Assessment Scale - Teacher Informant', 
                'assessment_group' => str_slug('ADHD'), 
                'type' => 'score',
                'slug' => str_slug('Assessment Scale - Teacher Informant'),
                'is_active' => 1,
                'role_code' => json_encode(array("school", "staff"))
            ],
            [
                'name' => 'Assessment Follow-up - Parent Informant', 
                'desc' => 'Assessment Follow-up - Parent Informant', 
                'assessment_group' => str_slug('ADHD'), 
                'type' => 'score',
                'slug' => str_slug('Assessment Follow-up - Parent Informant'),
                'is_active' => 1,
                'role_code' => json_encode(array("school", "student"))
            ],
            [
                'name' => 'Assessment Follow-up - Teacher Informant', 
                'desc' => 'Assessment Follow-up - Teacher Informant', 
                'assessment_group' => str_slug('ADHD'), 
                'type' => 'score',
                'slug' => str_slug('Assessment Follow-up - Teacher Informant'),
                'is_active' => 1,
                'role_code' => json_encode(array("school", "staff"))
            ]
    	];

        DB::table('forms')->insert($forms);

        $insert_questions = [
        	[
                'name' => 'Symptoms (Assessment Scale Parent)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Performance (Assessment Scale Parent)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Symptoms (Assessment Scale Teacher)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Performance (Assessment Scale Teacher)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Classroom Behavioral Performance (Assessment Scale Teacher)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Symptoms (Assessment Follow-up Parent)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Performance (Assessment Follow-up Parent)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Side Effects: Has your child experienced any of the following side effects or problems in the past week?',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Symptoms (Assessment Follow-up Teacher)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Performance (Assessment Follow-up Teacher)',
                'type' => 'sub_question',
                'is_active' => 1,
            ],
            [
                'name' => 'Side Effects: Has the child experienced any of the following side effects or problems in the past week?',
                'type' => 'sub_question',
                'is_active' => 1,
            ]
    	];

        DB::table('questions')->insert($insert_questions);

        $insert_answers = [
    		[
                'name' => 'Occasionally',
                'is_active' => 1,
            ],
            [
                'name' => 'Very Often',
                'is_active' => 1,
            ],
            [
                'name' => 'Excellent',
                'is_active' => 1,
            ],
            [
                'name' => 'Above Average',
                'is_active' => 1,
            ],
            [
                'name' => 'Average',
                'is_active' => 1,
            ],
            [
                'name' => 'Somewhat of a Problem',
                'is_active' => 1,
            ],
            [
                'name' => 'Problematic',
                'is_active' => 1,
            ],
            [
                'name' => 'Mild',
                'is_active' => 1,
            ],
            [
                'name' => 'Moderate',
                'is_active' => 1,
            ],
            [
                'name' => 'Severe',
                'is_active' => 1,
            ]            
    	];

        DB::table('answers')->insert($insert_answers);


    	$questions 		= json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$answers 		= json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$forms 			= json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$radio_type 	= 'radio';

		// Log::info($answers);

        $current_form_id = $forms["Assessment Scale - Parent Informant"];
        $form_questions = [
            ['question_id' => $questions["Symptoms (Assessment Scale Parent)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Performance (Assessment Scale Parent)"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);


        $current_form_id = $forms["Assessment Scale - Teacher Informant"];
        $form_questions = [
            ['question_id' => $questions["Symptoms (Assessment Scale Teacher)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Performance (Assessment Scale Teacher)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Classroom Behavioral Performance (Assessment Scale Teacher)"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

        $current_form_id = $forms["Assessment Follow-up - Parent Informant"];
        $form_questions = [
            ['question_id' => $questions["Symptoms (Assessment Follow-up Parent)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Performance (Assessment Follow-up Parent)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Side Effects: Has your child experienced any of the following side effects or problems in the past week?"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);


        DB::table('form_questions')->insert($form_questions);

        $current_form_id = $forms["Assessment Follow-up - Teacher Informant"];
        $form_questions = [
            ['question_id' => $questions["Symptoms (Assessment Follow-up Teacher)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Performance (Assessment Follow-up Teacher)"],'form_id' => $current_form_id],
            ['question_id' => $questions["Side Effects: Has the child experienced any of the following side effects or problems in the past week?"],'form_id' => $current_form_id],
        ];

        DB::table('form_questions')->insert($form_questions);

    	# Symptoms Assement Scale parent subquestion
    	$current_question_id = $questions['Symptoms (Assessment Scale Parent)'];

    	$new_sub_questions = [
    		[
                'name' => 'Does not pay attention to details or makes careless mistakes with, for example, homework',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty keeping attention to what needs to be done',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not seem to listen when spoken to directly',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not follow through when given directions and fails to finish activities (not due to refusal or failure to understand)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty organizing tasks and activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Avoids, dislikes, or does not want to start tasks that require ongoing mental effort',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Loses things necessary for tasks or activities (toys, assignments, pencils, or books)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is easily distracted by noises or other stimuli',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is forgetful in daily activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Fidgets with hands or feet or squirms in seat',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Leaves seat when remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Runs about or climbs too much when remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty playing or beginning quiet play activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is “on the go” or often acts as if “driven by a motor”',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Talks too much',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blurts out answers before questions have been completed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty waiting his or her turn',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Interrupts or intrudes in on others’ conversations and/or activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Argues with adults',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Loses temper',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Actively defies or refuses to go along with adults’ requests or rules',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Deliberately annoys people',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blames others for his or her mistakes or misbehaviors',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is touchy or easily annoyed by others',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is angry or resentful',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is spiteful and wants to get even',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Bullies, threatens, or intimidates others',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Starts physical fights',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Lies to get out of trouble or to avoid obligations (ie,“cons” others)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is truant from school (skips school) without permission',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is physically cruel to people',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has stolen things that have value',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Deliberately destroys others’ property',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has used a weapon that can cause serious harm (bat, knife, brick, gun)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is physically cruel to animals',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has deliberately set fires to cause damage',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has broken into someone else’s home, business, or car',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has stayed out at night without permission',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has run away from home overnight',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has forced someone into sexual activity',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is fearful, anxious, or worried',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is afraid to try new things for fear of making mistakes',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Feels worthless or inferior',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blames self for problems, feels guilty',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Feels lonely, unwanted, or unloved; complains that “no one loves him or her”',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is sad, unhappy, or depressed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is self-conscious or easily embarrassed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
		];

		DB::table('questions')->insert($new_sub_questions);

		// Performance Assement Scale Parent
		$current_question_id = $questions['Performance (Assessment Scale Parent)'];

		$new_sub_questions = [
			[
                'name' => 'Overall school performance',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Reading',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Writing',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Mathematics',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Relationship with parents',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Relationship with siblings',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Relationship with peers',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Participation in organized activities (eg, teams)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        // Symptoms Assement Scale Teacher
        $current_question_id = $questions['Symptoms (Assessment Scale Teacher)'];

        $new_sub_questions = [
			[
                'name' => 'Fails to give attention to details or makes careless mistakes in schoolwork',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty sustaining attention to tasks or activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not seem to listen when spoken to directly',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not follow through on instructions and fails to finish schoolwork (not due to oppositional behavior or failure to understand)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty organizing tasks and activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Avoids, dislikes, or is reluctant to engage in tasks that require sustained mental effort',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Loses things necessary for tasks or activities (school assignments, pencils, or books)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is easily distracted by extraneous stimuli',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is forgetful in daily activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Fidgets with hands or feet or squirms in seat',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Leaves seat in classroom or in other situations in which remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Runs about or climbs excessively in situations in which remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty playing or engaging in leisure activities quietly',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is “on the go” or often acts as if “driven by a motor”',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Talks excessively',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blurts out answers before questions have been completed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty waiting in line',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Interrupts or intrudes on others (eg, butts into conversations/games)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Loses temper',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Actively defies or refuses to comply with adult’s requests or rules',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is angry or resentful',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is spiteful and vindictive',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Bullies, threatens, or intimidates others',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Initiates physical fights',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Lies to obtain goods for favors or to avoid obligations (eg, “cons” others)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is physically cruel to people',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has stolen items of nontrivial value',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Deliberately destroys others’ property',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is fearful, anxious, or worried',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is self-conscious or easily embarrassed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is afraid to try new things for fear of making mistakes',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Feels worthless or inferior',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blames self for problems; feels guilty',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Feels lonely, unwanted, or unloved; complains that “no one loves him or her”',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is sad, unhappy, or depressed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        $current_question_id = $questions['Performance (Assessment Scale Teacher)'];

        $new_sub_questions = [
			[
                'name' => 'Reading',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Mathematics',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Written expression',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        $current_question_id = $questions['Classroom Behavioral Performance (Assessment Scale Teacher)'];

        $new_sub_questions = [
			[
                'name' => 'Relationship with peers',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Following directions',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Disrupting class',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Assignment completion',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Organizational skills',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);


        $current_question_id = $questions['Symptoms (Assessment Follow-up Parent)'];

        $new_sub_questions = [
			[
                'name' => 'Does not pay attention to details or makes careless mistakes with, for example, homework',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty keeping attention to what needs to be done',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not seem to listen when spoken to directly',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not follow through when given directions and fails to finish activities (not due to refusal or failure to understand)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty organizing tasks and activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Avoids, dislikes, or does not want to start tasks that require ongoing mental effort',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Loses things necessary for tasks or activities (toys, assignments, pencils, or books)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is easily distracted by noises or other stimuli',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is forgetful in daily activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Fidgets with hands or feet or squirms in seat',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Leaves seat when remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Runs about or climbs too much when remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty playing or beginning quiet play activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is “on the go” or often acts as if “driven by a motor”',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Talks too much',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blurts out answers before questions have been completed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty waiting his or her turn',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Interrupts or intrudes in on others’ conversations and/or activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        // Performance Assement Follow-up Parent
		$current_question_id = $questions['Performance (Assessment Follow-up Parent)'];

		$new_sub_questions = [
			[
                'name' => 'Overall school performance',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Reading',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Writing',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Mathematics',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Relationship with parents',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Relationship with siblings',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Relationship with peers',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Participation in organized activities (eg, teams)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);


        $current_question_id = $questions['Symptoms (Assessment Follow-up Teacher)'];

        $new_sub_questions = [
			[
                'name' => 'Does not pay attention to details or makes careless mistakes with, for example, homework',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty keeping attention to what needs to be done',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not seem to listen when spoken to directly',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Does not follow through when given directions and fails to finish activities (not due to refusal or failure to understand)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty organizing tasks and activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Avoids, dislikes, or does not want to start tasks that require ongoing mental effort',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Loses things necessary for tasks or activities (toys, assignments, pencils, or books)',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is easily distracted by noises or other stimuli',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is forgetful in daily activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Fidgets with hands or feet or squirms in seat',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Leaves seat when remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Runs about or climbs too much when remaining seated is expected',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty playing or beginning quiet play activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Is “on the go” or often acts as if “driven by a motor”',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Talks too much',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Blurts out answers before questions have been completed',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Has difficulty waiting his or her turn',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Interrupts or intrudes in on others’ conversations and/or activities',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        $current_question_id = $questions['Performance (Assessment Follow-up Teacher)'];

        $new_sub_questions = [
        	[
                'name' => 'Reading',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Mathematics',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Written expression',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
			[
                'name' => 'Relationship with peers',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Following directions',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Disrupting class',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Assignment completion',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Organizational skills',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        $current_question_id = $questions['Side Effects: Has the child experienced any of the following side effects or problems in the past week?'];

        $new_sub_questions = [
        	[
                'name' => 'Headache',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Stomachache',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Change of appetite—explain below',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Trouble sleeping',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Irritability in the late morning, late afternoon, or evening—explain below',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Socially withdrawn—decreased interaction with others',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Extreme sadness or unusual crying',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Dull, tired, listless behavior',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Tremors/feeling shaky',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Repetitive movements, tics, jerking, twitching, eye blinking—explain below',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Picking at skin or fingers, nail biting, lip or cheek chewing—explain below',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ],
            [
                'name' => 'Sees or hears things that aren’t there',
                'type' => $radio_type,
                'is_active' => 1,
                'parent_id' => $current_question_id
            ]
        ];

        DB::table('questions')->insert($new_sub_questions);

        // Form Question Answers
        $current_question_id 	= $questions['Symptoms (Assessment Scale Parent)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 0],
			['question_id' => $current_question_id, 'answer_id' => $answers["Occasionally"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Often"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Very Often"], 'jump_to_question_id' => null, 'score' => 3]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Performance (Assessment Scale Parent)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Excellent"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Above Average"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Average"], 'jump_to_question_id' => null, 'score' => 3],
			['question_id' => $current_question_id, 'answer_id' => $answers["Somewhat of a Problem"], 'jump_to_question_id' => null, 'score' => 4],
			['question_id' => $current_question_id, 'answer_id' => $answers["Problematic"], 'jump_to_question_id' => null, 'score' => 5]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Symptoms (Assessment Scale Teacher)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 0],
			['question_id' => $current_question_id, 'answer_id' => $answers["Occasionally"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Often"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Very Often"], 'jump_to_question_id' => null, 'score' => 3]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Performance (Assessment Scale Teacher)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Excellent"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Above Average"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Average"], 'jump_to_question_id' => null, 'score' => 3],
			['question_id' => $current_question_id, 'answer_id' => $answers["Somewhat of a Problem"], 'jump_to_question_id' => null, 'score' => 4],
			['question_id' => $current_question_id, 'answer_id' => $answers["Problematic"], 'jump_to_question_id' => null, 'score' => 5]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Classroom Behavioral Performance (Assessment Scale Teacher)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Excellent"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Above Average"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Average"], 'jump_to_question_id' => null, 'score' => 3],
			['question_id' => $current_question_id, 'answer_id' => $answers["Somewhat of a Problem"], 'jump_to_question_id' => null, 'score' => 4],
			['question_id' => $current_question_id, 'answer_id' => $answers["Problematic"], 'jump_to_question_id' => null, 'score' => 5]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Symptoms (Assessment Follow-up Parent)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 0],
			['question_id' => $current_question_id, 'answer_id' => $answers["Occasionally"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Often"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Very Often"], 'jump_to_question_id' => null, 'score' => 3]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Performance (Assessment Follow-up Parent)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Excellent"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Above Average"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Average"], 'jump_to_question_id' => null, 'score' => 3],
			['question_id' => $current_question_id, 'answer_id' => $answers["Somewhat of a Problem"], 'jump_to_question_id' => null, 'score' => 4],
			['question_id' => $current_question_id, 'answer_id' => $answers["Problematic"], 'jump_to_question_id' => null, 'score' => 5]
		];

        DB::table('form_question_answers')->insert($form_question_answers);


		$current_question_id 	= $questions['Symptoms (Assessment Follow-up Parent)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 0],
			['question_id' => $current_question_id, 'answer_id' => $answers["Occasionally"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Often"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Very Often"], 'jump_to_question_id' => null, 'score' => 3]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Performance (Assessment Follow-up Parent)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Excellent"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Above Average"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Average"], 'jump_to_question_id' => null, 'score' => 3],
			['question_id' => $current_question_id, 'answer_id' => $answers["Somewhat of a Problem"], 'jump_to_question_id' => null, 'score' => 4],
			['question_id' => $current_question_id, 'answer_id' => $answers["Problematic"], 'jump_to_question_id' => null, 'score' => 5]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id  = $questions['Side Effects: Has your child experienced any of the following side effects or problems in the past week?'];

        $form_question_answers = [
            ['question_id' => $current_question_id, 'answer_id' => $answers["None"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Mild"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Severe"], 'jump_to_question_id' => null, 'score' => 0]
        ];

        DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Symptoms (Assessment Follow-up Teacher)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 0],
			['question_id' => $current_question_id, 'answer_id' => $answers["Occasionally"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Often"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Very Often"], 'jump_to_question_id' => null, 'score' => 3]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Performance (Assessment Follow-up Teacher)'];

		$form_question_answers = [
			['question_id' => $current_question_id, 'answer_id' => $answers["Excellent"], 'jump_to_question_id' => null, 'score' => 1],
			['question_id' => $current_question_id, 'answer_id' => $answers["Above Average"], 'jump_to_question_id' => null, 'score' => 2],
			['question_id' => $current_question_id, 'answer_id' => $answers["Average"], 'jump_to_question_id' => null, 'score' => 3],
			['question_id' => $current_question_id, 'answer_id' => $answers["Somewhat of a Problem"], 'jump_to_question_id' => null, 'score' => 4],
			['question_id' => $current_question_id, 'answer_id' => $answers["Problematic"], 'jump_to_question_id' => null, 'score' => 5]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Side Effects: Has the child experienced any of the following side effects or problems in the past week?'];

		$form_question_answers = [
            ['question_id' => $current_question_id, 'answer_id' => $answers["None"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Mild"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Moderate"], 'jump_to_question_id' => null, 'score' => 0],
            ['question_id' => $current_question_id, 'answer_id' => $answers["Severe"], 'jump_to_question_id' => null, 'score' => 0]
        ];

		DB::table('form_question_answers')->insert($form_question_answers);

    }
}
