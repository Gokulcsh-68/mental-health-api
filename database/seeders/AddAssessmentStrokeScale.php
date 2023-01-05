<?php

namespace Database\Seeders;

use App\Entities\Form;
use App\Entities\Question;
use DB;
use Illuminate\Database\Seeder;

class AddAssessmentStrokeScale extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {


		$new_forms = [
		 ['slug' => 'stroke-scale-reconstitution-tnkase', 
		 	'name' => 'Reconstitution Instructions for TNKase', 
		 	'desc' => 'Reconstitution Instructions for TNKase',
		 	'assessment_group'=> 'stroke-scale',
		 	'role_code' => json_encode(["admin", "provider", "hospitalgroup", "hospital", "folio"])
		 ],
		 ['slug' => 'stroke-scale-administration-tnkase', 'name' =>
		 	'Administration Instructions for TNKase', 
		 	'desc' => 'Administration Instructions for TNKase',
		 	'assessment_group'=> 'stroke-scale',
		 	'role_code' => json_encode(["admin", "provider", "hospitalgroup", "hospital", "folio"])
		 ]
		];
		DB::table('forms')->insertOrIgnore($new_forms);


		$new_sub_questions = [
		 [
		 	'name' => 'Determine the correct dose of TNKase based on patient weight.  TNKase is for IV administration only.',
		 	'additional_info' => json_encode(['image_url'=>'tnkase-recon-step-1.genecoreimg.320.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],
		 [
		 	'name' => 'Aseptically WITHDRAW 10 mL of Sterile Water for Injection, USP, using the B-D 10 mL syringe with TwinPak™ Dual Cannula Device included in the kit. Do not use Bacteriostatic Water for Injection, USP.',
		 	'additional_info' => json_encode(['image_url'=>'tnkase-recon-step-2.genecoreimg.480.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],
		 [
		 	'name' => 'INJECT entire contents (10 mL) into the TNKase vial, directing the diluent into the powder. Slight foaming upon reconstitution is not unusual; any large bubbles will dissipate if the product is allowed to stand undisturbed for several minutes.',
		 	'additional_info' => json_encode(['image_url'=>'tnkase-recon-step-3.genecoreimg.480.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],
		 [
		 	'name' => 'GENTLY SWIRL until contents are completely dissolved. DO NOT SHAKE. The solution should be colourless or pale yellow and transparent. Once the appropriate dose of TNKase is drawn into the syringe, stand the shield vertically and recap the red tab cannula.',
		 	'additional_info' => json_encode(['image_url'=>'tnkase-recon-step-4.genecoreimg.120.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],
		 [
		 	'name' => 'USE UPON RECONSTITUTION. If not used immediately, refrigerate the solution (which does not contain bacterial preservative) at 2–8°C (36–46°F) and use it within 8 hours. DO NOT FREEZE. The final concentration of TNKase is 5 mg/mL.',
		 	'additional_info' => null,
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],		
		 [
		 	'name' => 'Determine the correct dose of TNKase based on patient weight. TNKase is for IV administration only.',
		 	'additional_info' => json_encode(['image_url'=>'admin-step1.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],
		 [
		 	'name' => 'WITHDRAW the appropriate volume of solution based on patient weight. The recommended total dose should not exceed 50 mg. Discard the solution remaining in the vial.',
		 	'additional_info' => json_encode(['image_url'=>'admin-step2.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ],
		 [
		 	'name' => 'FLUSH a dextrose-containing line with a saline-containing solution prior to and following administration (precipitation may occur when TNKase is administered in an IV line containing dextrose). ADMINISTER as an IV BOLUS over 5 seconds.',
		 	'additional_info' => json_encode(['image_url'=>'admin-step3.gif']),
		 	'type' => 'instructions',
		 	'is_active' => 1
		 ]
		];
		DB::table('questions')->insertOrIgnore($new_sub_questions);

		$forms = json_decode(Form::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);
		$questions = json_decode(Question::pluck('id', 'name')->toJson(JSON_UNESCAPED_UNICODE) , true);


		$current_form_id = $forms["Reconstitution Instructions for TNKase"];
		$current_form_id2 = $forms["Administration Instructions for TNKase"];

		$form_questions = [
			[
				'question_id' => $questions["Determine the correct dose of TNKase based on patient weight.  TNKase is for IV administration only."],
		 		'form_id' => $current_form_id
		 	],[
				'question_id' => $questions["Aseptically WITHDRAW 10 mL of Sterile Water for Injection, USP, using the B-D 10 mL syringe with TwinPak™ Dual Cannula Device included in the kit. Do not use Bacteriostatic Water for Injection, USP."],
		 		'form_id' => $current_form_id
		 	],[
				'question_id' => $questions["INJECT entire contents (10 mL) into the TNKase vial, directing the diluent into the powder. Slight foaming upon reconstitution is not unusual; any large bubbles will dissipate if the product is allowed to stand undisturbed for several minutes."],
		 		'form_id' => $current_form_id
		 	],[
				'question_id' => $questions["GENTLY SWIRL until contents are completely dissolved. DO NOT SHAKE. The solution should be colourless or pale yellow and transparent. Once the appropriate dose of TNKase is drawn into the syringe, stand the shield vertically and recap the red tab cannula."],
		 		'form_id' => $current_form_id
		 	],[
				'question_id' => $questions["USE UPON RECONSTITUTION. If not used immediately, refrigerate the solution (which does not contain bacterial preservative) at 2–8°C (36–46°F) and use it within 8 hours. DO NOT FREEZE. The final concentration of TNKase is 5 mg/mL."],
		 		'form_id' => $current_form_id
		 	],[
				'question_id' => $questions["Determine the correct dose of TNKase based on patient weight. TNKase is for IV administration only."],
		 		'form_id' => $current_form_id2
		 	],[
				'question_id' => $questions["WITHDRAW the appropriate volume of solution based on patient weight. The recommended total dose should not exceed 50 mg. Discard the solution remaining in the vial."],
		 		'form_id' => $current_form_id2
		 	],[
				'question_id' => $questions["FLUSH a dextrose-containing line with a saline-containing solution prior to and following administration (precipitation may occur when TNKase is administered in an IV line containing dextrose). ADMINISTER as an IV BOLUS over 5 seconds."],
		 		'form_id' => $current_form_id2
		 	]
		];

		DB::table('form_questions')->insertOrIgnore($form_questions);


		// form_questions


    }
}
