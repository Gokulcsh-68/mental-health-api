<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('masters')->where('master_type_slug','reaction')->update(['is_active'=> 0]);

        $reactions = [
            ['master_type_slug' => 'reaction','name' => "abdominal pain and/or pain", 'slug' => str_slug("NWabdominal pain and/or pain"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abdominal swelling", 'slug' => str_slug("NWabdominal swelling"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abnormal blood clotting", 'slug' => str_slug("NWabnormal blood clotting"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abnormal reflexes", 'slug' => str_slug("NWabnormal reflexes"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "abnormal thirst", 'slug' => str_slug("NWabnormal thirst"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "anaphylactic shock", 'slug' => str_slug("NWanaphylactic shock"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "anxiety and/or feeling of impending doom", 'slug' => str_slug("NWanxiety and/or feeling of impending doom"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "blood infection", 'slug' => str_slug("NWblood infection"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "chest tightness and/or discomfort", 'slug' => str_slug("NWchest tightness and/or discomfort"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "constipation", 'slug' => str_slug("NWconstipation"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "cough", 'slug' => str_slug("NWcough"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "coughing up blood", 'slug' => str_slug("NWcoughing up blood"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "diarrhea", 'slug' => str_slug("NWdiarrhea"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "difficulty swallowing", 'slug' => str_slug("NWdifficulty swallowing"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "dizziness and/or light headedness", 'slug' => str_slug("NWdizziness and/or light headedness"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "easy bruising", 'slug' => str_slug("NWeasy bruising"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "elevated liver enzymes", 'slug' => str_slug("NWelevated liver enzymes"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "enlarged glands", 'slug' => str_slug("NWenlarged glands"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "excessive crying of infant", 'slug' => str_slug("NWexcessive crying of infant"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "excessive sleeping", 'slug' => str_slug("NWexcessive sleeping"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "facial weakness", 'slug' => str_slug("NWfacial weakness"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fainting and/or loss of consciousness", 'slug' => str_slug("NWfainting and/or loss of consciousness"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fast breathing", 'slug' => str_slug("NWfast breathing"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fast heart rate", 'slug' => str_slug("NWfast heart rate"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fatigue", 'slug' => str_slug("NWfatigue"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "fever", 'slug' => str_slug("NWfever"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "flushing", 'slug' => str_slug("NWflushing"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "frequent urination", 'slug' => str_slug("NWfrequent urination"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "gas", 'slug' => str_slug("NWgas"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "green or yellow phlegm", 'slug' => str_slug("NWgreen or yellow phlegm"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "growth problem", 'slug' => str_slug("NWgrowth problem"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hallucinations", 'slug' => str_slug("NWhallucinations"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "headache", 'slug' => str_slug("NWheadache"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hearing changes", 'slug' => str_slug("NWhearing changes"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "heart murmur", 'slug' => str_slug("NWheart murmur"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "heart palpitations", 'slug' => str_slug("NWheart palpitations"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "heartburn", 'slug' => str_slug("NWheartburn"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hiccough", 'slug' => str_slug("NWhiccough"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "high blood pressure", 'slug' => str_slug("NWhigh blood pressure"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hives (red, raised, itchy bumps)", 'slug' => str_slug("NWhives (red, raised, itchy bumps)"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "hyperventilation", 'slug' => str_slug("NWhyperventilation"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "insomnia", 'slug' => str_slug("NWinsomnia"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "itching or numbness or tingling", 'slug' => str_slug("NWitching or numbness or tingling"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "itchy, watery eyes", 'slug' => str_slug("NWitchy, watery eyes"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "jaundice or yellow skin", 'slug' => str_slug("NWjaundice or yellow skin"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "lack of coordination", 'slug' => str_slug("NWlack of coordination"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "leakage of stool", 'slug' => str_slug("NWleakage of stool"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "leakage of urine", 'slug' => str_slug("NWleakage of urine"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "loss of appetite", 'slug' => str_slug("NWloss of appetite"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "low blood count", 'slug' => str_slug("NWlow blood count"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "low blood pressure", 'slug' => str_slug("NWlow blood pressure"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "memory loss", 'slug' => str_slug("NWmemory loss"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "muscle aches", 'slug' => str_slug("NWmuscle aches"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nasal congestion / runny nose", 'slug' => str_slug("NWnasal congestion / runny nose"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nausea and/or vomiting", 'slug' => str_slug("NWnausea and/or vomiting"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nausea only", 'slug' => str_slug("NWnausea only"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "noisy breathing", 'slug' => str_slug("NWnoisy breathing"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "nosebleed", 'slug' => str_slug("NWnosebleed"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "painful breathing", 'slug' => str_slug("NWpainful breathing"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "painful urination", 'slug' => str_slug("NWpainful urination"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "paleness", 'slug' => str_slug("NWpaleness"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "paralysis", 'slug' => str_slug("NWparalysis"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "problem walking", 'slug' => str_slug("NWproblem walking"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "rash", 'slug' => str_slug("NWrash"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "reduced urination", 'slug' => str_slug("NWreduced urination"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "retention of urine", 'slug' => str_slug("NWretention of urine"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "seizures", 'slug' => str_slug("NWseizures"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "shock", 'slug' => str_slug("NWshock"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "shortness of breath", 'slug' => str_slug("NWshortness of breath"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "smell or taste disturbance", 'slug' => str_slug("NWsmell or taste disturbance"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "sneezing", 'slug' => str_slug("NWsneezing"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "speech problem", 'slug' => str_slug("NWspeech problem"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "stiff neck", 'slug' => str_slug("NWstiff neck"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "sweating", 'slug' => str_slug("NWsweating"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "swelling", 'slug' => str_slug("NWswelling"), 'is_active' => 1],
            ['master_type_slug' => 'reaction','name' => "throat pain", 'slug' => str_slug("NWthroat pain"), 'is_active' => 1]

        ];

        DB::table('masters')->insert($reactions);
    }
}
