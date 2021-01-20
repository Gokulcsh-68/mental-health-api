<?php

namespace Database\Seeders;

use DB;
use App\Entities\Question;
use App\Entities\Answer;
use App\Entities\Form;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Log;

class AssementFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::unprepared(file_get_contents(__DIR__ . '\source\AssessmentFormDump.sql'));

    	$questions 				= json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$answers 				= json_decode(Answer::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$forms 					= json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$radio_type 			= 'radio';

		// Log::info($answers);

    	# healthy heart subquestion
    	$current_question_id = $questions['Thinking back on the past 30 days, please check yes or no for each statement. You may choose “yes” for more than one statement.'];

    	$new_sub_questions = [
    		['parent_id' => $current_question_id, 'name' => 
					 	'I rarely or never do any physical activities','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do some light or moderate physical activities, but not every week','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do some light physical activity every week','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do moderate physical activities every week, but less than 30 minutes a day,
					5 days a week','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do vigorous physical activities every week, but less than 20 minutes a day, 3
					days a week','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do 30 minutes or more per day of moderate physical activities, 5 or more
					days a week','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do 20 minutes or more per day of vigorous physical activities, 3 or more
					days a week','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do activities to increase muscle strength, such as lifting weights, once a
					week or more','type'=> $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' => 
					 	'I do activities to improve flexibility, such as stretching or yoga, once a week
					or more','type'=> $radio_type,'is_active' => 1]
		];

		DB::table('questions')->insert($new_sub_questions);

		$current_question_id = $questions['Please think about what you usually ate or drank during the past 30 days. Read each item carefully and indicate one response for each. How often did you...'];

		$new_sub_questions = [
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat bacon or sausage? (Do not include low-fat,
			light, or turkey varieties.)', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat processed meat (for example, lunch meat, hot dogs made of beef or pork, spam, corned beef)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat whole grain bread (for example, whole
			wheat, rye, oatmeal, or pumpernickel sandwich
			bread or rolls, corn tortillas)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat bread from processed flour (for example,
			white sandwich bread or rolls, round pueblo
			bread, flour tortillas)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat Frybread or other fried pastries?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat other baked goods (for example, doughnuts,
			Danish, coffee cake, cookies, pies, or cakes)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'drink regular soft drinks/pop/soda (for
			example, Slushees, Coke, bottled drinks like
			Snapple)? (Do not include diet drinks.)', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'drink 100% fruit juice (for example, orange,
			grapefruit, apple, and grape juices). (Do not
			count fruit drinks, such as Kool-Aid, lemonade,
			Cranberry Juice Cocktail, Hi-C, and Tang.)', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'add sugar (or honey) and/or creamer to your
			coffee or tea?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat fruit? Count fresh, frozen, dried, or canned
			fruit. Do not count juices.', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'use regular fat salad dressing or mayonnaise,
			including on salad and sandwiches? Do not
			include low-fat, light, or diet dressings.', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat lettuce or green leafy salad (for example,
			cabbage and spinach, with or without other
			vegetables)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat French fries, fried potatoes, tater tots or
			hash brown potatoes?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat cooked dried beans (for example, refried
			beans, baked beans, bean soup, pork and beans)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat “red” meat (for example, beef, pork or salt
			pork, veal, lamb, liver, kidneys)?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat fish, chicken, game?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat vegetables (for example, squash, okra, corn,
			zucchini, seaweed, kelp)? Count any form of
			vegetable – raw, cooked, canned, or frozen. Do
			not count lettuce salads, white potatoes, cooked
			dried beans, or rice.', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'eat fast food from a restaurant or store (for
			example, hamburgers, pizza, fried chicken,
			chimichangas/tacos)?', 'type' => $radio_type,'is_active' => 1]
		];

		DB::table('questions')->insert($new_sub_questions);

		$current_question_id = $questions['How often do you do the following things? Mark your answer with an X.'];

		$new_sub_questions = [
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you use fresh vegetables instead of canned vegetables?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you use bouillon cubes when you cook?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you read food labels to choose foods with a low-sodium content?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you add salt to fruit?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you add salt to the water when you cook beans, rice, pasta, or vegetables?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you use a saltshaker at the table?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you fill your saltshaker with a mixture of herbs and spices instead of salt?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you choose fruits and vegetables instead of
			potato chips, french fries, or pork rinds?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you eat low-fat cheese instead of regular
			cheese?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you read food labels to help you choose foods
			lower in saturated fat, trans fat, and cholesterol?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you use fresh vegetables instead of canned
			vegetables?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you remove the skin before cooking chicken?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you drain the fat and throw it away when you
			cook ground meat?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you choose fat-free or low-fat salad dressing
			or mayonnaise instead of regular?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you read labels to choose foods lower in
			calories?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you bake or grill foods instead of frying them?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you serve more vegetables on your plate than
			meat?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you serve yourself large portions of food?', 'type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Do you eat fruits instead of desserts or snacks that
			contain large amounts of sugar?', 'type' => $radio_type,'is_active' => 1]
		];

		DB::table('questions')->insert($new_sub_questions);

		$current_question_id = $questions["During the past 7 days, how much have you been bothered by any of the following problems?"];

		$new_sub_questions = [
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Stomach pain','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Back pain','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Pain in your arms, legs, or joints (knees, hips, etc.)','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Menstrual cramps or other problems with your periods WOMEN ONLY','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Headaches','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Chest pain','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Dizziness','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Fainting spells','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Feeling your heart pound or race','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Shortness of breath','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Pain or problems during sexual intercourse','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Constipation, loose bowels, or diarrhea','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Nausea, gas, or indigestion','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Feeling tired or having low energy','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'Trouble sleeping','type' => $radio_type,'is_active' => 1]
		 ];

		 DB::table('questions')->insert($new_sub_questions);

		 $current_question_id = $questions["In the past SEVEN (7) DAYS...."];

		 $new_sub_questions = [
			 ['parent_id' => $current_question_id, 'name' => 
			 	'I was irritated more than people knew.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'I felt angry','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'I felt like I was ready to explode.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'I was grouchy.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' => 
			 	'I felt annoyed.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt fearful.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt anxious.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt worried.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I found it hard to focus on anything other than my anxiety.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt nervous.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt uneasy.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt tense.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt worthless.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt that I had nothing to look forward to.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt helpless.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt sad.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt like a failure.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt depressed.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt unhappy.','type' => $radio_type,'is_active' => 1],
			 ['parent_id' => $current_question_id, 'name' =>
			 	'I felt hopeless.','type' => $radio_type,'is_active' => 1]
		 ];

		 DB::table('questions')->insert($new_sub_questions);

		 $current_question_id = $questions["Eligibility for TPA"];

 		 $new_sub_questions = [
 			['parent_id' => $current_question_id, 'name' =>
			 	'Age ≥18','type' => $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' =>
			 	'Clinical diagnosis of ischemic stroke causing neurological deficit','type' => $radio_type,'is_active' => 1],
			['parent_id' => $current_question_id, 'name' =>
			 	'Time of symptom onset <4.5 hours See Additional Warnings to tPA at 3-4.5hr below','type' => $radio_type,'is_active' => 1]
		 ];

		DB::table('questions')->insert($new_sub_questions);

		$current_question_id = $questions["Absolute Contraindications to TPA"];

		$new_sub_questions = [
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Intracranial hemorrhage on CT','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Clinical presentation suggests subarachnoid hemorrhage','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Neurosurgery, head trauma, or stroke in past 3 months','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Uncontrolled hypertension (>185 mmHg SBP or >110 mmHg DBP)','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'History of intracranial hemorrhage','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Known intracranial arteriovenous malformation, neoplasm, or aneurysm','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Active internal bleeding','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Suspected/confirmed endocarditis','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Known bleeding diathesis
		(1) Platelet count < 100,000; (2) Patient has received heparin within 48 hours and has an elevated aPTT (greater than upper limit of normal for laboratory); (3) Current use of oral anticoagulants (ex: warfarin) and INR >1.7; (4)Current use of direct thrombin inhibitors or direct factor Xa inhibitors','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Abnormal blood glucose (<50 mg/dL)','type' => $radio_type,'is_active' => 1]
		];

		DB::table('questions')->insert($new_sub_questions);

		$current_question_id = $questions["Relative Contraindications/Warnings to TPA"];

		$new_sub_questions = [
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Only minor or rapidly improving stroke symptoms','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Major surgery or serious non-head trauma in the previous 14 days','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'History of gastrointestinal or urinary tract hemorrhage within 21 days','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Seizure at stroke onset','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Recent arterial puncture at a noncompressible site','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Recent lumbar puncture','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Post myocardial infarction pericarditis','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Pregnancy','type' => $radio_type,'is_active' => 1]
		];

		DB::table('questions')->insert($new_sub_questions);

		$current_question_id = $questions["Additional Warnings to TPA >3hr Onset"];
		$new_sub_questions = [
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Age >80 years','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'History of prior stroke and diabetes','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'Any active anticoagulant use (even with INR <1.7)','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'NIHSS >25','type' => $radio_type,'is_active' => 1],
		 ['parent_id' => $current_question_id, 'name' =>
		 	'CT shows multilobar infarction (hypodensity >1/3 cerebral hemisphere)','type' => $radio_type,'is_active' => 1]
		];
		DB::table('questions')->insert($new_sub_questions);


		# Question mapping

		# healthy heart

		$current_form_id = $forms["Healthy Heart"];
		$form_questions = [
		['question_id' => $questions["When was the last time you had your blood pressure checked?"],'form_id' => $current_form_id],
		['question_id' => $questions["The LAST time you had your blood pressure checked, was it normal or high?"],
		 'form_id' => $current_form_id],
		['question_id' => $questions["Have you EVER been told by a doctor, nurse, or other health professional that you have high blood pressure?"],'form_id' => $current_form_id],
		['question_id' => $questions["If yes, and if you are female, was this only when you were pregnant?"],'form_id' => $current_form_id],
		['question_id' => $questions["Are you currently taking medicine for your high blood pressure?"],'form_id' => $current_form_id],
		['question_id' => $questions["Are you changing your eating habits to help lower or control your blood pressure?"],'form_id' => $current_form_id],
		['question_id' => $questions["Are you cutting down on salt to help lower or control your blood pressure?"],'form_id' => $current_form_id],
		['question_id' => $questions["Are you reducing alcohol use to help lower or control your blood pressure?"],'form_id' => $current_form_id],
		['question_id' => $questions["Are you exercising to help lower or control your blood pressure?"],'form_id' => $current_form_id],
		['question_id' => $questions["Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?"],'form_id' => $current_form_id],
		['question_id' => $questions["About how long has it been since you last had your blood cholesterol checked?"],'form_id' => $current_form_id],
		['question_id' => $questions["The last time you had your blood cholesterol checked, was it normal or high?"],'form_id' => $current_form_id],
		['question_id' => $questions["Have you EVER been told by a doctor, nurse or other health professional that your blood cholesterol is high?"],'form_id' => $current_form_id],
		['question_id' => $questions["If so, when were you told that your blood cholesterol was high?"],'form_id' => $current_form_id],
		['question_id' => $questions["How many days per week do you do moderate physical activities for at least 30 minutes?"],'form_id' => $current_form_id],
		['question_id' => $questions["How many days per week do you do vigorous physical activities for at least 20 minutes?"],'form_id' => $current_form_id],
		['question_id' => $questions["Thinking back on the past 30 days, please check yes or no for each statement. You may choose “yes” for more than one statement."],'form_id' => $current_form_id],
		['question_id' => $questions["Over the past 30 days in general, how many hours per day did you usually spend watching television, sitting at a computer, playing video games, doing beadwork, or other activities that don’t require much physical activity?"],'form_id' => $current_form_id],
		['question_id' => $questions["Do you plan to increase the amount of physical activity you get every week?"],'form_id' => $current_form_id],
		['question_id' => $questions["Please think about what you usually ate or drank during the past 30 days. Read each item carefully and indicate one response for each. How often did you..."],'form_id' => $current_form_id],
		['question_id' => $questions["What kind of milk did you usually use? (Pick the one that you used most often in the past 30 days.) What kind of milk did you usually use? (Pick the one that you used most often in the past 30 days.)"],'form_id' => $current_form_id],
		['question_id' => $questions["What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one, choose the one used most often)?"],'form_id' => $current_form_id],
		['question_id' => $questions["How often do you do the following things? Mark your answer with an X."],'form_id' => $current_form_id],
		['question_id' => $questions["Are you able to buy or grow low-cost vegetables?"],'form_id' => $current_form_id],
		['question_id' => $questions["In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?"],
		'form_id' => $current_form_id],
		['question_id' => $questions["Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)"],
		'form_id' => $current_form_id],
		 ['question_id' => $questions["Thinking over the past 30 days, including today, how many days during this time did you smoke?"],'form_id' => $current_form_id],
		['question_id' => $questions["About how many cigarettes a day do you now smoke?"],'form_id' => $current_form_id],
		['question_id' => $questions["About how many years have you been smoking?"],'form_id' => $current_form_id],
		['question_id' => $questions["In the past year, how many times have you quit smoking for at least 24 hours?"],'form_id' => $current_form_id],
		['question_id' => $questions["Are you seriously thinking of quitting smoking?"],'form_id' => $current_form_id],
		['question_id' => $questions["Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?"],'form_id' => $current_form_id],
		['question_id' => $questions["Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?"],'form_id' => $current_form_id],
		['question_id' => $questions["Do you think swelling of the feet and legs is a symptom of a heart attack?"],'form_id' => $current_form_id],
		['question_id' => $questions["Do you think chest pain or discomfort are symptoms of a heart attack?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think tingling in the fingers and toes are symptoms of a heart attack?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think shortness of breath is a symptom of a heart attack?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sudden confusion or trouble speaking are symptoms of a stroke?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think feeling sick to your stomach is a symptom of a stroke?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sharp pain in the jaw or mouth is a symptom of a stroke?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sudden chest pain or discomfort are symptoms of a stroke?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you think severe headache with no known cause is a symptom of a stroke?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["If you thought someone was having a heart attack or a stroke, what is the first thing you would do?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Can the Body Mass Index (BMI) Chart tell you if you are overweight?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Does your liver make all the cholesterol your body needs to keep you healthy?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Can eating foods that are high in sodium increase your risk of high blood pressure?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Does lard have a low amount of saturated fat?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Can eating too much saturated fat and trans fat raise your cholesterol level?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Is a blood pressure of 140/90 mmHg considered high?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Can being overweight or obese put you at risk for developing high blood cholesterol?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Is being physically active a way to reduce your risk for heart disease?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Is it true that only people with high blood cholesterol should follow a heart healthy diet?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Can nonsmokers die from secondhand smoke?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["How confident are you in filling out medical forms by yourself?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["How often do you prefer that someone (like a family member or someone else) help you read medical materials?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you have a TV?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Which system do you have?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you have a personal computer in your home?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Is it a Windows or Apple system?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["How confident are you in using your computer?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you have Internet access?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you have an e-mail account that you check regularly?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Do you have a cell phone?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Are you able to send and received text messages using your cell phone?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["What are some of the reasons you would not be interested in getting text messages about heart health?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["How much do you currently weigh without shoes?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["How tall are you without shoes?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Are you male or female?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["How old are you today?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["What is your ethnicity?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["What is your race?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["If you marked “American Indian or Alaska Native” in the previous question, what tribe do you most closely identify with?"],
		 'form_id' => $current_form_id],
		 ['question_id' => $questions["What is the highest grade in school you completed?"],'form_id' => $current_form_id],
		 ['question_id' => $questions["Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you."],
		 'form_id' => $current_form_id]
		];

		DB::table('form_questions')->insert($form_questions);

		# Other physical 

		$form_questions = [
		 ['question_id' => $questions["During the past 7 days, how much have you been bothered by any of the following problems?"],'form_id' => $forms["Physical Symptoms"]],
		 ['question_id' => $questions["In the past SEVEN (7) DAYS...."],'form_id' => $forms["Anger"]],
		 ['question_id' => $questions["In the past SEVEN (7) DAYS...."],'form_id' => $forms["Anxiety"]],
		 ['question_id' => $questions["In the past SEVEN (7) DAYS...."],'form_id' => $forms["Depression"]],
		 ['question_id' => $questions["Question 1"],'form_id' => $forms["Mania"]],
		 ['question_id' => $questions["Question 2"],'form_id' => $forms["Mania"]],
		 ['question_id' => $questions["Question 3"],'form_id' => $forms["Mania"]],
		 ['question_id' => $questions["Question 4"],'form_id' => $forms["Mania"]],
		 ['question_id' => $questions["Question 5"],'form_id' => $forms["Mania"]],
		 ['question_id' => $questions["Sudden numbness"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Sudden Weakness"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Sudden Disability"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Vision"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Gait / Posture"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Seizure"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Sudden Pain / Ache"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["F—Face: Ask the person to smile. Does one side of the face droop"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["A—Arms: Ask the person to raise both arms. Does one arm drift downward"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["T—Time: If you see any of these signs, call +919840056700"],'form_id' => $forms["Symptoms & Signs"]],
		 ['question_id' => $questions["Level of consciousness"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Ask month and age"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Blink eyes & squeeze hands"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Horizontal extraocular movements"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Visual fields"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Facial palsy"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Left arm motor drift"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Right arm motor drift"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Left leg motor drift"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Right leg motor drift"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Limb Ataxia"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Sensation"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Language/aphasia"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Dysarthria"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Extinction/inattention"],'form_id' => $forms["NIH Stroke Scale/Score (NIHSS)"]],
		 ['question_id' => $questions["Eligibility for TPA"],'form_id' => $forms["TPA Contraindications for Ischemic Stroke"]],
		 ['question_id' => $questions["Absolute Contraindications to TPA"],'form_id' => $forms["TPA Contraindications for Ischemic Stroke"]],
		 ['question_id' => $questions["Relative Contraindications/Warnings to TPA"],'form_id' => $forms["TPA Contraindications for Ischemic Stroke"]],
		 ['question_id' => $questions["Additional Warnings to TPA >3hr Onset"],'form_id' => $forms["TPA Contraindications for Ischemic Stroke"]],
		 ['question_id' => $questions["Age"],'form_id' => $forms["THRIVE Score for Stroke Outcome"]],
		 ['question_id' => $questions["NIH Stroke Scale"],'form_id' => $forms["THRIVE Score for Stroke Outcome"]],
		 ['question_id' => $questions["History of hypertension"],'form_id' => $forms["THRIVE Score for Stroke Outcome"]],
		 ['question_id' => $questions["History of diabetes mellitus"],'form_id' => $forms["THRIVE Score for Stroke Outcome"]],
		 ['question_id' => $questions["History of atrial fibrillation"],'form_id' => $forms["THRIVE Score for Stroke Outcome"]]
		 ];

		DB::table('form_questions')->insert($form_questions);


		# Answers Mapping
		#1
		$current_question_id 	= $questions['When was the last time you had your blood pressure checked?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past year (anytime less than 12 months ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past 2 years (more than 1 year ago but less than 2 years ago)"], 
		 	'jump_to_question_id' => null],
		 ['question_id' => $current_question_id,
		 	'answer_id' => $answers["Within the past 5 years (more than 2 years ago but less than 5 years ago)"],
		 	'jump_to_question_id' => null],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Five or more years ago"], 'jump_to_question_id' => null],

		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null],

		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Never had it checked"], 
		 	$questions["Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?"]]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		#2
		$current_question_id 	= $questions['The LAST time you had your blood pressure checked, was it normal or high?'];

		$form_question_answers = [
			['question_id' => $current_question_id , 'answer_id' => $answers["Normal"], 'jump_to_question_id' => null],
			['question_id' => $current_question_id , 'answer_id' => $answers["High"], 'jump_to_question_id' => null],
			['question_id' => $current_question_id , 'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		#3
		$current_question_id 	= $questions['Have you EVER been told by a doctor, nurse, or other health professional that you have high blood pressure?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => $questions["Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?"]],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"],'jump_to_question_id' => $questions["Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?"]]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		#4
		$current_question_id 	= $questions['If yes, and if you are female, was this only when you were pregnant?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"],'jump_to_question_id' => $questions["Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?"]],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"],'jump_to_question_id' => $questions["Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?"]]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		 #5
		 $current_question_id 	= $questions['Are you currently taking medicine for your high blood pressure?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],

		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],

		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#6
		$current_question_id 	= $questions['Are you changing your eating habits to help lower or control your blood pressure?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#7
		$current_question_id 	= $questions['Are you cutting down on salt to help lower or control your blood pressure?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id , 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id , 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id , 
		 	'answer_id' => $answers["Do Not Use Salt"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id , 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#8
		$current_question_id 	= $questions['Are you reducing alcohol use to help lower or control your blood pressure?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Do Not Drink"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#9
		$current_question_id 	= $questions['Are you exercising to help lower or control your blood pressure?'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#10
		$current_question_id 	= $questions['Blood cholesterol is a fatty substance found in the blood. Have you ever had your blood cholesterol checked?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 
		 	$questions["If so, when were you told that your blood cholesterol was high?"]],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"],
		 	 $questions["If so, when were you told that your blood cholesterol was high?"]]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		#11
		$current_question_id 	= $questions['About how long has it been since you last had your blood cholesterol checked?'];
		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past year (anytime less than 12 months ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past 2 years (more than 1 year ago but less than 2 years ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past 5 years (more than 2 years ago but less than 5 years ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Five or more years ago"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#12
		$current_question_id 	= $questions['The last time you had your blood cholesterol checked, was it normal or high?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Normal"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["High"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#13
		$current_question_id 	= $questions['Have you EVER been told by a doctor, nurse or other health professional that your blood cholesterol is high?'];
		$form_question_answers = [
		['question_id' => $current_question_id, 
		  'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["No"],
		  $questions["If so, when were you told that your blood cholesterol was high?"]],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["Don’t Know/Not Sure"],
		  $questions["If so, when were you told that your blood cholesterol was high?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#14
		$current_question_id 	= $questions['If so, when were you told that your blood cholesterol was high?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past year (anytime less than 12 months ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past 2 years (more than 1 year ago but less than 2 years ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Within the past 5 years (more than 2 years ago but less than 5 years ago)"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Five or more years ago"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);


		 #15
		 $current_question_id 	= $questions['How many days per week do you do moderate physical activities for at least 30 minutes?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Days per week (Please write “0” if the answer is “none.”)"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#16
		$current_question_id 	= $questions['How many days per week do you do vigorous physical activities for at least 20 minutes?'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Days per week (Please write “0” if the answer is “none.”)"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#17
		$current_question_id 	= $questions['Thinking back on the past 30 days, please check yes or no for each statement. You may choose “yes” for more than one statement.'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#18
		$current_question_id 	= $questions['Over the past 30 days in general, how many hours per day did you usually spend watching television, sitting at a computer, playing video games, doing beadwork, or other activities that don’t require much physical activity?'];
		$form_question_answers = [
		['question_id' => $current_question_id, 
		  'answer_id' => $answers["1 hour or less"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["2 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["3 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["4 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["5 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["6 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["7 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["8 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["9 hours"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		  'answer_id' => $answers["10 hours or more"], 'jump_to_question_id' => null]
		  ];

		 DB::table('form_question_answers')->insert($form_question_answers);


		#19
		$current_question_id 	= $questions['Do you plan to increase the amount of physical activity you get every week?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes, I intend to in the next 30 days"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes, I intend to in the next 6 months"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No, and I do not intend to in the next 6 months"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#20
		$current_question_id 	= $questions['Please think about what you usually ate or drank during the past 30 days. Read each item carefully and indicate one response for each. How often did you...'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["More than once a day"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["About once a day"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["2-3 times a week"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["About once a week"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["1-3 times a month"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Less than once a month"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#21
		$current_question_id 	= $questions['What kind of milk did you usually use? (Pick the one that you used most often in the past 30 days.) What kind of milk did you usually use? (Pick the one that you used most often in the past 30 days.)'];
		 $form_question_answers = [
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Whole milk"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["2% fat"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["1% fat"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["1⁄2% fat"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Non-fat or skim"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Soy/lactose free"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Canned milk"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Powdered milk"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Did not use milk in past 30 days"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#22
		$current_question_id 	= $questions['What kinds of fat or oil did you usually use in cooking in the past 30 days (if more than one, choose the one used most often)?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Pam/cooking spray"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Stick margarine/butter/margarine blend/soft-tub"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Lard, fatback, bacon fat"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Crisco"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Vegetable oil/olive oil/corn oil"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);


		#23
		$current_question_id 	= $questions['How often do you do the following things? Mark your answer with an X.'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Never"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Sometimes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Most of the Time"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["All of the Time"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Does Not Apply"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#24
		$current_question_id 	= $questions['Are you able to buy or grow low-cost vegetables?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#25
		$current_question_id 	= $questions['In the future, do you intend to reduce the amount of fat you eat so it is lower than it is now?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes, and I intend to in the next 30 days"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes, and I intend to in the next 6 months"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No, and I do not intend to in the next 6 months"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);


		 #26
		 $current_question_id 	= $questions['Do you smoke cigarettes now? (For these questions, we are not interested in the tobacco you may smoke for ceremonial use.)'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => $questions["Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #27
		 $current_question_id 	= $questions['Thinking over the past 30 days, including today, how many days during this time did you smoke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["days"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #28
		 $current_question_id 	= $questions['About how many cigarettes a day do you now smoke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["cigarettes a day"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#29
		$current_question_id 	= $questions['About how many years have you been smoking?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["years"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #30
		 $current_question_id 	= $questions['In the past year, how many times have you quit smoking for at least 24 hours?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["times"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #31
		 $current_question_id 	= $questions['Are you seriously thinking of quitting smoking?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes, within the next 30 days"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes, within the next 6 months"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No, not thinking of quitting"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #32
		 $current_question_id 	= $questions['Do you think pain or discomfort in the jaw, neck, or back are symptoms of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #33
		 $current_question_id 	= $questions['Do you think feeling weak, lightheaded, or faint are symptoms of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #34
		 $current_question_id 	= $questions['Do you think swelling of the feet and legs is a symptom of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);


		 #35
		 $current_question_id 	= $questions['Do you think chest pain or discomfort are symptoms of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #36
		 $current_question_id 	= $questions['Do you think sudden trouble seeing in one or both eyes is a symptom of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #37
		 $current_question_id 	= $questions['Do you think tingling in the fingers and toes are symptoms of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #38
		 $current_question_id 	= $questions['Do you think pain or discomfort in the arms or shoulder are symptoms of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #39
		 $current_question_id 	= $questions['Do you think shortness of breath is a symptom of a heart attack?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #40
		 $current_question_id 	= $questions['Do you think sudden confusion or trouble speaking are symptoms of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #41
		 $current_question_id 	= $questions['Do you think sudden numbness or weakness of face, arm, or leg, especially on one side, are symptoms of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #42
		 $current_question_id 	= $questions['Do you think feeling sick to your stomach is a symptom of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #43
		 $current_question_id 	= $questions['Do you think sharp pain in the jaw or mouth is a symptom of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #44
		 $current_question_id 	= $questions['Do you think sudden trouble seeing in one or both eyes is a symptom of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #45
		 $current_question_id 	= $questions['Do you think sudden chest pain or discomfort are symptoms of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #46
		 $current_question_id 	= $questions['Do you think sudden trouble walking, dizziness, or loss of balance are symptoms of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #47
		 $current_question_id 	= $questions['Do you think severe headache with no known cause is a symptom of a stroke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #48
		 $current_question_id 	= $questions['If you thought someone was having a heart attack or a stroke, what is the first thing you would do?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Take them to the hospital"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Tell them to call their doctor"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Call 911"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Call their spouse or a family member"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Do something else"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #49
		 $current_question_id 	= $questions['Can a large waist (>35 inches for women or >40 inches for men) increase your risk of heart disease?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #50
		 $current_question_id 	= $questions['Can the Body Mass Index (BMI) Chart tell you if you are overweight?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #51
		 $current_question_id 	= $questions['Does your liver make all the cholesterol your body needs to keep you healthy?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #52
		 $current_question_id 	= $questions['Can eating foods that are high in sodium increase your risk of high blood pressure?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #53
		 $current_question_id 	= $questions['Does lard have a low amount of saturated fat?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #54
		 $current_question_id 	= $questions['Can eating too much saturated fat and trans fat raise your cholesterol level?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #55
		 $current_question_id 	= $questions['Is a blood pressure of 140/90 mmHg considered high?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #56
		 $current_question_id 	= $questions['Can being overweight or obese put you at risk for developing high blood cholesterol?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #57
		 $current_question_id 	= $questions['Is being physically active a way to reduce your risk for heart disease?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #58
		 $current_question_id 	= $questions['Is it true that only people with high blood cholesterol should follow a heart healthy diet?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #59
		 $current_question_id 	= $questions['Can nonsmokers die from secondhand smoke?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know/Not Sure"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #60
		 $current_question_id 	= $questions['How often do you have a hard time understanding written information about your health that you get from your clinic? (This might include information from a doctor or nurse.)'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Always"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Often"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Sometimes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Rarely"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Never"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);


		 #61
		 $current_question_id 	= $questions['How confident are you in filling out medical forms by yourself?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Extremely"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Quite a bit"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Somewhat"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["A little bit"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Not at all"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #62
		 $current_question_id 	= $questions['How often do you prefer that someone (like a family member or someone else) help you read medical materials?'];

		 $form_question_answers = [
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Always"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Often"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Sometimes"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Rarely"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Never"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #63
		 $current_question_id 	= $questions["Which of the following numbers represents the lowest risk? For example, which would you most like to hear from a doctor about your risk for a medical condition?"];

		 $form_question_answers = [
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["1 in 10 people"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["1 in 100 people"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["1 in 1000 people"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #64
		 $current_question_id 	= $questions['If the chance of getting a health condition is 20 out of 100 people, this would be the same as having a what percent (%) chance of getting the condition?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["2%"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["20%"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["200%"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #65
		 $current_question_id 	= $questions['A prescription says “Take one tablet by mouth every 6 hours.” If you take your first tablet at 7 a.m., when should you take your second tablet?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["10 a.m"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["12 p.m"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["1 p.m"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["6 p.m"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["7 p.m"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#66
		$current_question_id 	= $questions['Normal fasting blood sugar is 70-100. If your blood sugar today is 140, is your blood sugar normal?'];
		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #67
		 $current_question_id 	= $questions['Do you have a TV?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => $questions["Do you have a personal computer in your home?"]]
		 ];

		DB::table('form_question_answers')->insert($form_question_answers);

		 #68
		 $current_question_id 	= $questions['Do you have a gaming system you hook up to your TV? By this we mean something like the Nintendo Wii, Xbox, or Sony Playstation?'];
		 $form_question_answers = [
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["No"],'jump_to_question_id' => $questions["Do you have a personal computer in your home?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #69
		 $current_question_id 	= $questions['Which system do you have?'];
		 $form_question_answers = [
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Nintendo Wii"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Xbox"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Sony Playstation"], 'jump_to_question_id' => null],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Other (Please specify:"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#70
		$current_question_id 	= $questions['Do you have a personal computer in your home?'];
		$form_question_answers 	= [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => $questions["Do you have a cell phone?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #71
		 $current_question_id 	= $questions['Is it a Windows or Apple system?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Windows"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Apple"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #72
		 $current_question_id 	= $questions['How confident are you in using your computer?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Very confident"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Fairly confident"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Not at all confident"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #73
		 $current_question_id 	= $questions['Do you have Internet access?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #74
		 $current_question_id 	= $questions['Do you have an e-mail account that you check regularly?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 	];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #75
		 $current_question_id 	= $questions['Do you have a cell phone?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 
		 	$questions["How much do you currently weigh without shoes?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #76
		 $current_question_id 	= $questions['Are you able to send and received text messages using your cell phone?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 
		 	$questions["How much do you currently weigh without shoes?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #77
		 $current_question_id 	= $questions['Would you be willing to receive text messages about heart disease and heart-healthy living on your cell phone?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"],
		 	 $questions["How much do you currently weigh without shoes?"]]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #78
		 $current_question_id 	= $questions['What are some of the reasons you would not be interested in getting text messages about heart health?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Too expensive"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I’m not worried about my heart heatlh"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Other"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #79
		 $current_question_id 	= $questions['How much do you currently weigh without shoes?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["pounds"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#80
		$current_question_id 	= $questions['How tall are you without shoes?'];
		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["feet"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["inches"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #81
		 $current_question_id 	= $questions['Are you male or female?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Male"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Female"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #82
		 $current_question_id 	= $questions['How old are you today?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["years old"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #83
		 $current_question_id 	= $questions['What is your ethnicity?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Hispanic or Latino of any race"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Not Hispanic or Latino"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #84
		 $current_question_id 	= $questions['What is your race?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["American Indian or Alaska Native"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Asian"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Black or African American"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Native Hawaiian or Pacific Islander"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["White"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Other (Please specify:"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Don’t Know"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		#85
		$current_question_id 	= $questions['If you marked “American Indian or Alaska Native” in the previous question, what tribe do you most closely identify with?'];
		$form_question_answers = [
			['question_id' => $current_question_id,'answer_id' => null, 'jump_to_question_id' => null]
		];

		 DB::table('form_question_answers')->insert($form_question_answers);
		 
		 #86
		 $current_question_id 	= $questions['What is the highest grade in school you completed?'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["None"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["1st grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["2nd grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["3rd grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["4th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["5th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["6th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["7th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["8th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["9th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["10th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["11th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["12th grade"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["High School graduate/GED"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Vocational school"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Some college"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["College graduate"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Some graduate/professional school"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Graduate/professional degree"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 #87
		 $current_question_id 	= $questions['Please fill in the category below that best fits the total combined income before taxes of all people who lived in your household last year. This should include not only wages, salaries, and tips but also income from social security, pension, unemployment, or disability compensation, alimony, child support, welfare, or any other money income received by all household members – by you or anyone else living with you.'];
		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Nothing"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Less than $1,000"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$1,000 - $4,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$5,000 - $9,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$10,000 - $14,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$15,000 - $19,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$20,000 - $29,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$30,000 - $39,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$40,000 – $49,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$50,000 - $74,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["$75,000 - $99,999"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["More than $100,000"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		  # PsychiatricPhysicalSymptoms

		$current_question_id 	= $questions['During the past 7 days, how much have you been bothered by any of the following problems?'];

		$form_question_answers = [
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Not bothered at all"], 'jump_to_question_id' => null, 'score' => 0],
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Bothered a little"], 'jump_to_question_id' => null, 'score' => 1],
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Bothered a lot"], 'jump_to_question_id' => null, 'score' => 2]
		];
		DB::table('form_question_answers')->insert($form_question_answers);

		# PsychiatricAnger & PsychiatricAnxiety & PsychiatricDepression

		$current_question_id 	= $questions['In the past SEVEN (7) DAYS....'];

		$form_question_answers = [
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Never"], 'jump_to_question_id' => null, 'score' => 1],
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Rarely"], 'jump_to_question_id' => null, 'score' => 2],
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Sometimes"], 'jump_to_question_id' => null, 'score' => 3],
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Often"], 'jump_to_question_id' => null, 'score' => 4],
		 ['question_id' => $current_question_id, 'answer_id' => $answers["Always"], 'jump_to_question_id' => null,'score' => 5],
		];

		 
		 DB::table('form_question_answers')->insert($form_question_answers);

		 # PsychiatricMania

		$current_question_id 	= $questions['Question 1'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I do not feel happier or more cheerful than usual."], 'jump_to_question_id' => null,
		 	'score' => 1],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I occasionally feel happier or more cheerful than usual."], 'jump_to_question_id' => null, 'score' => 2],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I often feel happier or more cheerful than usual."], 'jump_to_question_id' => null, 'score' => 3],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I feel happier or more cheerful than usual most of the time."], 'jump_to_question_id' => null,'score' => 4],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I feel happier of more cheerful than usual all of the time."], 'jump_to_question_id' => null, 'score' => 5]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 $current_question_id 	= $questions['Question 2'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I do not feel more self-confident than usual."], 'jump_to_question_id' => null,'score' => 1],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I occasionally feel more self-confident than usual."], 'jump_to_question_id' => null, 'score' => 2],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I often feel more self-confident than usual."], 'jump_to_question_id' => null, 'score' => 3],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I frequently feel more self-confident than usual."], 'jump_to_question_id' => null,'score' => 4],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I feel extremely self-confident all of the time."], 'jump_to_question_id' => null, 'score' => 5]
		 ];

		  DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Question 3'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I do not need less sleep than usual."], 'jump_to_question_id' => null,'score' => 1],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I occasionally need less sleep than usual."], 'jump_to_question_id' => null, 'score' => 2],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I often need less sleep than usual."], 'jump_to_question_id' => null, 'score' => 3],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I frequently need less sleep than usual."], 'jump_to_question_id' => null,'score' => 4],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I can go all day and all night without any sleep and still not feel tired."],
		 	'jump_to_question_id' => null, 'score' => 5]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Question 4'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I do not talk more than usual."], 'jump_to_question_id' => null,'score' => 1],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I occasionally talk more than usual."], 'jump_to_question_id' => null, 'score' => 2],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I often talk more than usual."], 'jump_to_question_id' => null, 'score' => 3],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I frequently talk more than usual."], 'jump_to_question_id' => null,'score' => 4],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I talk constantly and cannot be interrupted."], 'jump_to_question_id' => null, 'score' => 5]
		 ];

		  DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Question 5'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I have not been more active (either socially, sexually, at work, home, or school) than usual."], 'jump_to_question_id' => null,'score' => 1],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I have occasionally been more active than usual."], 'jump_to_question_id' => null, 'score' => 2],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I have often been more active than usual."], 'jump_to_question_id' => null, 'score' => 3],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I have frequently been more active than usual."], 'jump_to_question_id' => null,'score' => 4],
		 
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["I am constantly more active or on the go all the time."], 'jump_to_question_id' => null, 'score' => 5]
		 ];

		DB::table('form_question_answers')->insert($form_question_answers);

		# Strokes Scale

		# StrokeScaleGeneral

		$current_question_id 	= $questions['Sudden numbness'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["face"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["arm"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["leg"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Sudden Weakness'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["face"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["arm"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["leg"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Sudden Disability'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["face"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["arm"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["leg"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Vision'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Blurring"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Double Vision"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Dropping of eye lid"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Loss of Vision"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Gait / Posture'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Lack of coordination"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Unable to move"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Seizure'];

		 $form_question_answers = [
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Sudden Pain / Ache'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Head"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Neck"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Other Parts of Body"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['F—Face: Ask the person to smile. Does one side of the face droop'];
		 $form_question_answers = [
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['A—Arms: Ask the person to raise both arms. Does one arm drift downward'];
		 $form_question_answers = [
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 $current_question_id 	= $questions['S—Speech: Ask the person to repeat a simple phrase. Is the speech slurred or strange'];

		 $form_question_answers = [
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 $current_question_id 	= $questions['T—Time: If you see any of these signs, call +919840056700'];

		 $form_question_answers = [
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 # nih-stroke-scale-score-nihss

		 $current_question_id 	= $questions['Level of consciousness'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Alert; keenly responsive"],'jump_to_question_id' => null, 'score' => 0],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Arouses to minor stimulation"],'jump_to_question_id' => null, 'score' => 1],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Requires repeated stimulation to arouse"],'jump_to_question_id' => null, 'score' => 2],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Movements to pain"],'jump_to_question_id' => null, 'score' => 2],
			 ['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Postures or unresponsive"],'jump_to_question_id' => null,'score' => 3]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Ask month and age'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Both questions right"],'jump_to_question_id' => null,'score' => 0],
			['question_id' => $current_question_id, 
			 	'answer_id' => $answers["1 question right"],'jump_to_question_id' => null,'score' => 1],
			['question_id' => $current_question_id, 
			 	'answer_id' => $answers["0 questions right"],'jump_to_question_id' => null,'score' => 2],
			['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Dysarthric/intubated/trauma/language barrier"],'jump_to_question_id' => null,'score' => 1],
			['question_id' => $current_question_id, 
			 	'answer_id' => $answers["Aphasic"],'jump_to_question_id' => null,'score' => 2]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Blink eyes & squeeze hands'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Performs both tasks"],'jump_to_question_id' => null,'score' => 0],
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Performs 1 task"],'jump_to_question_id' => null,'score' => 1],
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Performs 0 tasks"],'jump_to_question_id' => null,'score' => 2]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);


		$current_question_id 	= $questions['Horizontal extraocular movements'];

		$form_question_answers = [
			['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Normal"],'jump_to_question_id' => null,'score' => 0],
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Partial gaze palsy: can be overcome"],'jump_to_question_id' => null,'score' => 1],
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Partial gaze palsy: corrects with oculocephalic reflex"],'jump_to_question_id' => null,'score' => 1],
		 	['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Forced gaze palsy: cannot be overcome"],'jump_to_question_id' => null,'score' => 2]
		 ];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Blink eyes & squeeze hands'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No visual loss"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Partial hemianopia"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Complete hemianopia"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Patient is bilaterally blind"],'jump_to_question_id' => null,'score' => 3],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Bilateral hemianopia"],'jump_to_question_id' => null,'score' => 3]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Blink eyes & squeeze hands'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Normal symmetry"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Minor paralysis (flat nasolabial fold, smile asymmetry)"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Partial paralysis (lower face)"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Unilateral complete paralysis (upper/lower face)"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Bilateral complete paralysis (upper/lower face)"],'jump_to_question_id' => null,'score' => 3]
		 ];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Blink eyes & squeeze hands'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No drift for 10 seconds"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, but doesn't hit bed"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, hits bed"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Some effort against gravity"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No effort against gravity"],'jump_to_question_id' => null,'score' => 3],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No movement"],'jump_to_question_id' => null,'score' => 4],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Amputation/joint fusion"],'jump_to_question_id' => null,'score' => 0]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Right arm motor drift'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No drift for 10 seconds"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, but doesn't hit bed"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, hits bed"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Some effort against gravity"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No effort against gravity"],'jump_to_question_id' => null,'score' => 3],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No movement"],'jump_to_question_id' => null,'score' => 4],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Amputation/joint fusion"],'jump_to_question_id' => null,'score' => 0]
		 ];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Left leg motor drift'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No drift for 10 seconds"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, but doesn't hit bed"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, hits bed"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Some effort against gravity"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No effort against gravity"],'jump_to_question_id' => null,'score' => 3],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No movement"],'jump_to_question_id' => null,'score' => 4],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Amputation/joint fusion"],'jump_to_question_id' => null,'score' => 0]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Right leg motor drift'];

		 $form_question_answers = [
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No drift for 10 seconds"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, but doesn't hit bed"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Drift, hits bed"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Some effort against gravity"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No effort against gravity"],'jump_to_question_id' => null,'score' => 3],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No movement"],'jump_to_question_id' => null,'score' => 4],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Amputation/joint fusion"],'jump_to_question_id' => null,'score' => 0]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Limb Ataxia'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No ataxia"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Ataxia in 1 Limb"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Ataxia in 2 Limbs"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Does not understand"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Paralyzed"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Amputation/joint fusion"],'jump_to_question_id' => null,'score' => 0]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Sensation'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Normal; no sensory loss"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Mild-moderate loss: less sharp/more dull"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Mild-moderate loss: can sense being touched"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Complete loss: cannot sense being touched at all"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No response and quadriplegic"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Coma/unresponsive"],'jump_to_question_id' => null,'score' => 2]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Language/aphasia'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Normal; no aphasia"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Mild-moderate aphasia: some obvious changes, without significant limitation"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Severe aphasia: fragmentary expression, inference needed, cannot identify materials+"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Mute/global aphasia: no usable speech/auditory comprehension"],'jump_to_question_id' => null,'score' => 3],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Coma/unresponsive"],'jump_to_question_id' => null,'score' => 3]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Dysarthria'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Normal"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Mild-moderate dysarthria: slurring but can be understood"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Severe dysarthria: unintelligible slurring or out of proportion to dysphasia"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Mute/anarthric"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Intubated/unable to test"],'jump_to_question_id' => null,'score' => 0]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		$current_question_id 	= $questions['Extinction/inattention'];

		$form_question_answers = [
		['question_id' => $current_question_id, 
		 	'answer_id' => $answers["No abnormality"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Visual/tactile/auditory/spatial/personal inattention"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Extinction to bilateral simultaneous stimulation"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Profound hemi-inattention (ex: does not recognize own hand)"],'jump_to_question_id' => null,'score' => 2],
		 ['question_id' => $current_question_id, 
		 	'answer_id' => $answers["Extinction to >1 modality"],'jump_to_question_id' => null,'score' => 2]
		 ];

		 DB::table('form_question_answers')->insert($form_question_answers);

		 # tpa-contraindications-ischemic-stroke.sql

		 $form_question_answers = [
		  ['question_id' => $questions["Eligibility for TPA"],'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Eligibility for TPA"],'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Absolute Contraindications to TPA"],'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Absolute Contraindications to TPA"],'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Relative Contraindications/Warnings to TPA"],'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Relative Contraindications/Warnings to TPA"],'answer_id' => $answers["No"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Additional Warnings to TPA >3hr Onset"],'answer_id' => $answers["Yes"], 'jump_to_question_id' => null],
		  ['question_id' => $questions["Additional Warnings to TPA >3hr Onset"],'answer_id' => $answers["No"], 'jump_to_question_id' => null]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

		# thrive-score-stroke-outcome
 
		$form_question_answers = [
		 ['question_id' => $questions["Age"], 
		 	'answer_id' => $answers["years"],'jump_to_question_id' => null],
		 ['question_id' => $questions["NIH Stroke Scale"], 
		 	'answer_id' => $answers["Norm: 0 - 42 points"],'jump_to_question_id' => null]
		];

		DB::table('form_question_answers')->insert($form_question_answers);


		$form_question_answers = [
		 ['question_id' => $questions["History of hypertension"], 
		 	'answer_id' => $answers["Yes"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $questions["History of hypertension"], 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $questions["History of diabetes mellitus"], 
		 	'answer_id' => $answers["Yes"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $questions["History of diabetes mellitus"], 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => null,'score' => 0],
		 ['question_id' => $questions["History of atrial fibrillation"], 
		 	'answer_id' => $answers["Yes"],'jump_to_question_id' => null,'score' => 1],
		 ['question_id' => $questions["History of atrial fibrillation"], 
		 	'answer_id' => $answers["No"],'jump_to_question_id' => null,'score' => 0]
		];

		DB::table('form_question_answers')->insert($form_question_answers);

    }
}
