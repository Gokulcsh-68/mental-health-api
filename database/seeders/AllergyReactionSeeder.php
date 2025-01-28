<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AllergyReactionSeeder extends Seeder
{
    public function run()
    {
        $reactions = ["Abdominal cramping","Abdominal pain and/or swelling","Abnormal blood clotting","Abnormal reflexes","Abnormal thirst","Allergic conjunctivitis","Anaphylactic shock","Angioedema","Anxiety and/or feeling of impending doom","Blood infection","Burning sensation in the eyes","Changes in vision","Chest tightness and/or discomfort","Chills","Cold sweat","Constipation","Cough","Coughing up blood","Cramps","Dehydration","Diarrhea","Difficulty concentrating","Difficulty swallowing","Disorientation","Dizziness and/or lightheadedness","Dry mouth","Easy bruising","Elevated liver enzymes","Enlarged glands","Excessive crying in infants","Excessive sleeping","Facial weakness","Fainting and/or loss of consciousness","Fast breathing","Fast heart rate","Fatigue","Fever","Fever blisters","Flaky skin","Flushing","Frequent urination","Gas","Green or yellow phlegm","Growth problems","Hallucinations","Headache","Hearing changes","Heart murmur","Heart palpitations","Heartburn","Hiccough","High blood pressure","Hives (red, raised, itchy bumps)","Hypersensitivity to touch","Hyperventilation","Hypotension","Increased sensitivity to light","Insomnia","Itching or numbness or tingling","Itchy, watery eyes","Jaundice or yellow skin","Lack of coordination","Leakage of stool","Leakage of urine","Loss of appetite","Loss of consciousness","Low blood count","Low blood pressure","Memory loss","Muscle aches","Nasal congestion / runny nose","Nausea and/or vomiting","Nausea only","Noisy breathing","Nosebleed","Painful breathing","Painful urination","Paleness","Paralysis","Persistent cough","Photosensitivity","Problem walking","Rash","Reduced urination","Retention of urine","Seizures","Shock","Shortness of breath","Skin lesions","Skin peeling","Smell or taste disturbance","Sneezing","Speech problem","Stiff neck","Sweating","Swelling","Swollen face","Swollen lips","Throat pain","Unexplained weight loss","Weakness","Wheezing"];
        foreach ($reactions as $reaction) {
        
            $slug = strtolower(str_replace(' ', '_', $reaction));

                // Check if the record exists
                $existingMaster = DB::table('masters')
                    ->where('name', $reaction)
                    ->where('is_active', 0)
                    ->first();

                if ($existingMaster) {
                    // Append '-allergy' to slug if is_active is not 1
                    $slug .= '-reaction';
                }

                // Perform update or insert
                DB::table('masters')->updateOrInsert(
                    ['name' => $reaction],  // Where condition
                    [
                        'master_type_slug' => 'reaction',
                        'slug' => $slug,
                        'attributes' => json_encode(['allergy_type' => "Reaction", 'allergy_category' => "Reaction"]),
                        'is_active' => 1
                    ]
                );
        }
    }
}
