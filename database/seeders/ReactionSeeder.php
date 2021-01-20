<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            ['slug' => 'reaction']
        ];
        
        DB::table('master_types')->insert($master_types);

        $reactions = [
    		['master_type_slug' => 'reaction','name' => "None", 'slug' => "None", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abdominal pain and/or pain", 'slug' => "abdominal pain and/or pain", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abdominal swelling", 'slug' => "abdominal swelling", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abnormal blood clotting", 'slug' => "abnormal blood clotting", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abnormal reflexes", 'slug' => "abnormal reflexes", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abnormal thirst", 'slug' => "abnormal thirst", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "anaphylactic shock", 'slug' => "anaphylactic shock", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "anxiety and/or feeling of impending doom", 'slug' => "anxiety and/or feeling of impending doom", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "blood infection", 'slug' => "blood infection", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "chest tightness and/or discomfort", 'slug' => "chest tightness and/or discomfort", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "constipation", 'slug' => "constipation", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "cough", 'slug' => "cough", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "coughing up blood", 'slug' => "coughing up blood", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "diarrhea", 'slug' => "diarrhea", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "difficulty swallowing", 'slug' => "difficulty swallowing", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "dizziness and/or light", 'slug' => "dizziness and/or light", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "easy bruising", 'slug' => "easy bruising", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "elevated liver enzymes", 'slug' => "elevated liver enzymes", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "enlarged glands", 'slug' => "enlarged glands", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "excessive crying of infant", 'slug' => "excessive crying of infant", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "excessive sleeping", 'slug' => "excessive sleeping", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "facial weakness", 'slug' => "facial weakness", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fainting and/or loss of consciousness", 'slug' => "fainting and/or loss of consciousness", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fast breathing", 'slug' => "fast breathing", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fast heart rate", 'slug' => "fast heart rate", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fatigue", 'slug' => "fatigue", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fever", 'slug' => "fever", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "flushing", 'slug' => "flushing", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "frequent urination", 'slug' => "frequent urination", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "green or yellow phlegm", 'slug' => "green or yellow phlegm", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "growth problem", 'slug' => "growth problem", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hallucinations", 'slug' => "hallucinations", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "headache", 'slug' => "headache", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hearing changes", 'slug' => "hearing changes", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "heart murmur", 'slug' => "heart murmur", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "heart palpitations", 'slug' => "heart palpitations", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "heartburn", 'slug' => "heartburn", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hiccough", 'slug' => "hiccough", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "high blood pressure", 'slug' => "high blood pressure", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hives (red, raised, itchy bumps)", 'slug' => "hives (red, raised, itchy bumps)", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hyperventilation", 'slug' => "hyperventilation", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "insomnia", 'slug' => "insomnia", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "itching or numbness or tingling", 'slug' => "itching or numbness or tingling", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "itchy, watery eyes", 'slug' => "itchy, watery eyes", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "jaundice or yellow skin", 'slug' => "jaundice or yellow skin", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "lack of coordination", 'slug' => "lack of coordination", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "leakage of stool", 'slug' => "leakage of stool", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "leakage of urine", 'slug' => "leakage of urine", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "loss of appetite", 'slug' => "loss of appetite", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "low blood count", 'slug' => "low blood count", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "low blood pressure", 'slug' => "low blood pressure", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "memory loss", 'slug' => "memory loss", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "muscle aches", 'slug' => "muscle aches", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nasal congestion / runny nose", 'slug' => "nasal congestion / runny nose", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nausea and/or vomiting", 'slug' => "nausea and/or vomiting", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nausea only", 'slug' => "nausea only", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "noisy breathing", 'slug' => "noisy breathing", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nosebleed", 'slug' => "nosebleed", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "painful breathing", 'slug' => "painful breathing", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "painful urination", 'slug' => "painful urination", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "paleness", 'slug' => "paleness", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "paralysis", 'slug' => "paralysis", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "problem walking", 'slug' => "problem walking", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "rash", 'slug' => "rash", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "reduced urination", 'slug' => "reduced urination", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "retention of urine", 'slug' => "retention of urine", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "seizures", 'slug' => "seizures", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "shock", 'slug' => "shock", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "shortness of breath", 'slug' => "shortness of breath", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "smell or taste disturbance", 'slug' => "smell or taste disturbance", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "sneezing", 'slug' => "sneezing", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "speech problem", 'slug' => "speech problem", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "stiff neck", 'slug' => "stiff neck", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "sweating", 'slug' => "sweating", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "swelling", 'slug' => "swelling", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "throat pain", 'slug' => "throat pain", 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "Others",'slug' => "Others", 'is_active' => 1]
    	];

        DB::table('masters')->insert($reactions);
    }
}
