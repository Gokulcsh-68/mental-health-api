<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class ROSTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ros = [
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'weight_loss', 'label' => 'Weight Loss', 'data' => 'general_weight_loss'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'fever', 'label' => 'Fever', 'data' => 'general_fever'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'fatigue', 'label' => 'Fatigue', 'data' => 'general_fatigue'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'sweating', 'label' => 'Sweating', 'data' => 'general_Sweating'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'appetite_changes', 'label' => 'Appetite Changes', 'data' => 'general_appetite_changes'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'night_sweats', 'label' => 'Night Sweats', 'data' => 'general_night_sweats'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'weakness', 'label' => 'Weakness', 'data' => 'general_weakness'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'chills', 'label' => 'Chills', 'data' => 'general_chills'])],
            ['slug' => "general", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'general_notes_notes'])],

            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'hives', 'label' => 'Hives', 'data' => 'allergy_hives'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'swelling_of_lips_or_tongue', 'label' => 'Swelling of lips or tongue', 'data' => 'allergy_swelling_of_lips_or_tongue'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'hay_fever', 'label' => 'Hay Fever', 'data' => 'allergy_hay_fever'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'asthma', 'label' => 'Asthma', 'data' => 'allergy_asthma'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'eczema_sensitive', 'label' => 'Eczema/Sensitive', 'data' => 'allergy_eczema_sensitive'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'sensitivity_to_drugs_food,', 'label' => 'Sensitivity to drugs, food', 'data' => 'allergy_sensitivity_to_drugs_food'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'pollens_or_dander', 'label' => 'pollens, or Dander', 'data' => 'allergy_pollens_or_dander'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'autoimmune_disorder', 'label' => 'AutoImmune Disorder', 'data' => 'allergy_autoimmune_disorder'])],
            ['slug' => "allergy", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'allergy_notes_notes'])],

            ['slug' => "skin", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'rashes', 'label' => 'Rashes', 'data' => 'skin_rashes'])],
            ['slug' => "skin", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'itching', 'label' => 'Itching', 'data' => 'skin_itching'])],
            ['slug' => "skin", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'change_in_hair_or_nails', 'label' => 'Change in hair or nails', 'data' => 'skin_change_in_hair_or_nails'])],
            ['slug' => "skin", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'skin_lesions', 'label' => 'Skin Lesions', 'data' => 'skin_skin_lesions'])],
            ['slug' => "skin", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'skin_notes_notes'])],

            ['slug' => "head", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'headache', 'label' => 'Headache', 'data' => 'head_headache_notes'])],
            ['slug' => "head", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'head_injury', 'label' => 'head injury', 'data' => 'head_head_injury_notes'])],
            ['slug' => "head", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'head_notes_notes'])],

            ['slug' => "eye", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'change_in_vision', 'label' => 'Change in vision', 'data' => 'eye_change_in_vision'])],
            ['slug' => "eye", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'glasses_or_contacts', 'label' => 'Glasses or contacts', 'data' => 'eye_glasses_or_contacts'])],
            ['slug' => "eye", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'eye_pain', 'label' => 'Eye pain', 'data' => 'eye_eye_pain'])],
            ['slug' => "eye", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'double_vision', 'label' => 'Double vision', 'data' => 'eye_double_vision'])],
            ['slug' => "eye", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'flashing_lights', 'label' => 'Flashing lights', 'data' => 'eye_flashing_lights'])],
            ['slug' => "eye", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'glaucoma/cataracts', 'label' => 'Glaucoma/Cataracts', 'data' => 'eye_glaucoma/cataracts  '])],
            ['slug' => "eye", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'eye_notes_notes'])],

            ['slug' => "ears", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'change_in_hearing', 'label' => 'Change in hearing', 'data' => 'ears_change_in_hearing'])],
            ['slug' => "ears", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'ear_pain', 'label' => 'Ear pain', 'data' => 'ears_ear_pain'])],
            ['slug' => "ears", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'ear_discharge', 'label' => 'Ear discharge', 'data' => 'ears_ear_discharge'])],
            ['slug' => "ears", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'ear_ringing', 'label' => 'Ear Ringing', 'data' => 'ears_ear_ringing'])],
            ['slug' => "ears", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'ear_dizziness', 'label' => 'Ear Dizziness', 'data' => 'ears_ear_dizziness'])],
            ['slug' => "ears", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'ears_notes_notes'])],

            ['slug' => "nose", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'nose_bleeds', 'label' => 'Nose bleeds', 'data' => 'nose_nose_bleeds'])],
            ['slug' => "nose", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'nasal_stuffiness', 'label' => 'Nasal stuffiness', 'data' => 'nose_nasal_stuffiness'])],
            ['slug' => "nose", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'frequent_colds', 'label' => 'Frequent colds', 'data' => 'nose_frequent_colds'])],
            ['slug' => "nose", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'nose_notes_notes'])],

            ['slug' => "mouth/throat", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'bleeding_gums', 'label' => 'Bleeding gums', 'data' => 'mouth/throat_bleeding_gums'])],
            ['slug' => "mouth/throat", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'sore_tongue', 'label' => 'Sore tongue', 'data' => 'mouth/throat_sore_tongue'])],
            ['slug' => "mouth/throat", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'sore_throat', 'label' => 'Sore throat', 'data' => 'mouth/throat_sore_throat'])],
            ['slug' => "mouth/throat", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'hoarseness', 'label' => 'Hoarseness', 'data' => 'mouth/throat_hoarseness'])],
            ['slug' => "mouth/throat", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'mouth/throat_notes_notes'])],

            ['slug' => "neck", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'lumps', 'label' => 'Lumps', 'data' => 'neck_lumps'])],
            ['slug' => "neck", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'swollen_glands', 'label' => 'Swollen glands', 'data' => 'neck_Swollen glands'])],
            ['slug' => "neck", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'goiter', 'label' => 'Goiter', 'data' => 'neck_goiter'])],
            ['slug' => "neck", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'stiffness', 'label' => 'Stiffness', 'data' => 'neck_stiffness'])],
            ['slug' => "neck", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'neck_notes_notes'])],

            ['slug' => "breast", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'lumps', 'label' => 'Lumps', 'data' => 'breast_lumps'])],
            ['slug' => "breast", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'pain', 'label' => 'Pain', 'data' => 'breast_pain'])],
            ['slug' => "breast", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'nipple_discharge', 'label' => 'Nipple discharge', 'data' => 'breast_nipple_discharge'])],
            ['slug' => "breast", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'bse', 'label' => 'BSE (Breast Self-Examination)', 'data' => 'breast_bse'])],
            ['slug' => "breast", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'breast_notes_notes'])],

            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'shortness_of_breath', 'label' => 'Shortness of breath', 'data' => 'respiratory_shortness_of_breath_notes'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'cough', 'label' => 'Cough ', 'data' => 'respiratory_cough'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'production_of _phlegm_color', 'label' => 'Production of phlegm, color', 'data' => 'respiratory_production_of _phlegm_color'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'wheezing', 'label' => 'wheezing', 'data' => 'respiratory_wheezing_notes'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'coughing_up_blood', 'label' => 'Coughing up blood', 'data' => 'respiratory_coughing_up_blood'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'chest_pain', 'label' => 'Chest pain', 'data' => 'respiratory_chest_pain'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'dyspnea', 'label' => 'Dyspnea (Difficulty Breathing)', 'data' => 'respiratory_dyspnea'])],
            ['slug' => "respiratory", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'respiratory_notes_notes'])],

            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'chest_Pain', 'label' => 'Chest Pain', 'data' => 'cardiovascular_chest_Pain'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'palpitations', 'label' => 'palpitations', 'data' => 'cardiovascular_palpitations_notes'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'swelling_in_hands/feet', 'label' => 'Swelling in hands/feet', 'data' => 'cardiovascular_swelling_in_hands/feet'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'blue_fingers/toes', 'label' => 'Blue fingers/toes', 'data' => 'cardiovascular_blue_fingers/toes'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'high_blood_pressure', 'label' => 'High blood pressure', 'data' => 'cardiovascular_high_blood_pressure'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'skipping_heart_beats', 'label' => 'Skipping heart beats', 'data' => 'cardiovascular_paroxysmal_nocturnal_dyspnea_notes'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'heart_murmur', 'label' => 'Heart murmur', 'data' => 'cardiovascular_heart_murmur'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'hx_of_heart_medication', 'label' => 'HX of heart medication', 'data' => 'cardiovascular_hx_of_heart_medication'])],
            ['slug' => "cardiovascular", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'cardiovascular_notes_notes'])],

            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'abdominal_pain', 'label' => 'Abdominal Pain', 'data' => 'gastro_abdominal_pain'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'change_of_appetite', 'label' => 'Change of appetite', 'data' => 'gastro_change_of_appetite'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'weight_loss', 'label' => 'Weight Loss', 'data' => 'gastro_weight_loss'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'problems_swallowing', 'label' => 'Problems swallowing', 'data' => 'gastro_problems_swallowing'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'nausea', 'label' => 'Nausea', 'data' => 'gastro_nausea'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'heartburn', 'label' => 'Heartburn', 'data' => 'gastro_heartburn'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'vomiting', 'label' => 'Vomiting', 'data' => 'gastro_vomiting'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'Vomiting blood', 'label' => 'Vomiting blood', 'data' => 'gastro_Vomiting blood'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'constipation', 'label' => 'Constipation', 'data' => 'gastro_Constipation'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'diarrhoea', 'label' => 'Diarrhoea', 'data' => 'gastro_diarrhoea'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'change_in_bowel_habits', 'label' => 'Change in bowel habits', 'data' => 'gastro_change_in_bowel_habits'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'excessive_belching', 'label' => 'Excessive belching', 'data' => 'gastro_excessive_belching'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'excessive_flatus', 'label' => 'Excessive flatus', 'data' => 'gastro_excessive_flatus'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'yellow_color_of_skin', 'label' => 'Yellow color of skin', 'data' => 'gastro_yellow_color_of_skin'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'food_intolerance', 'label' => 'food intolerance', 'data' => 'gastro_food_intolerance'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'rectal_bleeding', 'label' => 'rectal bleeding', 'data' => 'gastro_rectal_bleeding'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'hemorrhoids', 'label' => 'hemorrhoids', 'data' => 'gastro_hemorrhoids'])],
            ['slug' => "gastro", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'gastro_notes_notes'])],

            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'difficulty_in_urination', 'label' => 'Difficulty in urination', 'data' => 'genito_urinary_difficulty_in_urination'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'pain_or_burning_on_urination', 'label' => 'Pain or burning on urination', 'data' => 'genito_urinary_pain_or_burning_on_urination'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'frequent_urination_at_night', 'label' => 'Frequent urination at night', 'data' => 'genito_urinary_frequent_urination_at_night'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'urgent_need_to_urinate', 'label' => 'Urgent need to urinate', 'data' => 'genito_urinary_urgent_need_to_urinate'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'incontinence_of_urine', 'label' => 'Incontinence of urine', 'data' => 'genito_urinary_incontinence_of_urine'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'dribbling', 'label' => 'Dribbling', 'data' => 'genito_urinary_Dribbling'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'decreased_urine_stream', 'label' => 'Decreased urine stream', 'data' => 'genito_urinary_decreased_urine_stream'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'blood_in_urine', 'label' => 'Blood in urine', 'data' => 'genito_urinary_blood_in_urine'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'uti/stones/prostate', 'label' => 'UTI/stones/prostate', 'data' => 'genito_urinary_uti/stones/prostate'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'infection', 'label' => 'Infection', 'data' => 'genito_urinary_infection'])],
            ['slug' => "genito_urinary", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'genito_urinary - male_notes_notes'])],

            ['slug' => "peripheral_vascular_disease", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'leg_cramps', 'label' => 'Leg cramps', 'data' => 'peripheral_vascular_disease_leg_cramps'])],
            ['slug' => "peripheral_vascular_disease", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'varicose_veins', 'label' => 'Varicose veins', 'data' => 'peripheral_vascular_disease_varicose_veins'])],
            ['slug' => "peripheral_vascular_disease", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'clots_in_veins', 'label' => 'Clots in veins', 'data' => 'peripheral_vascular_disease_clots_in_veins'])],
            ['slug' => "peripheral_vascular_disease", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'peripheral_vascular_disease_notes_notes'])],

            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'pain', 'label' => 'Pain', 'data' => 'musculoskeletal_pain'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'swelling', 'label' => 'Swelling', 'data' => 'musculoskeletal_swelling'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'stiffness', 'label' => 'Stiffness', 'data' => 'musculoskeletal_stiffness'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'decreased_joint_motion', 'label' => 'Decreased joint motion', 'data' => 'musculoskeletal_decreased_joint_motion'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'broken_bone', 'label' => 'Broken bone', 'data' => 'musculoskeletal_broken_bone'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'serious_sprains', 'label' => 'Serious sprains', 'data' => 'musculoskeletal_serious_sprains'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'Arthritis', 'label' => 'arthritis', 'data' => 'musculoskeletal_arthritis'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'gout', 'label' => 'Gout', 'data' => 'musculoskeletal_gout'])],
            ['slug' => "musculoskeletal", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'musculoskeletal_notes_notes'])],

            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'headaches', 'label' => 'headaches', 'data' => 'neurology_headaches'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'seizures', 'label' => 'seizures', 'data' => 'neurology_seizures'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'Syncope', 'label' => 'Syncope', 'data' => 'neurology_Syncope'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'paralysis', 'label' => 'paralysis', 'data' => 'neurology_paralysis'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'weakness', 'label' => 'Weakness', 'data' => 'neurology_weakness'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'loss_of_muscle_size', 'label' => 'Loss of muscle size', 'data' => 'neurology_loss_of_muscle_size'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'tremors', 'label' => 'tremors', 'data' => 'neurology_tremors'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'involuntary_movements', 'label' => 'involuntary movements', 'data' => 'neurology_involuntary_movements'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'numbness', 'label' => 'numbness', 'data' => 'neurology_numbness'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'incoordination', 'label' => 'Incoordination', 'data' => 'neurology_incoordination'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'feeling_of_pins_and_needles/tingles', 'label' => 'Feeling of “pins and needles/tingles”, Bleeding Ulcers', 'data' => 'neurology_feeling_of_pins_and_needles/tingles'])],
            ['slug' => "neurology", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'notes', 'data' => 'neurology_notes_notes'])],

            ['slug' => "hematology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'anemia', 'label' => 'Anemia', 'data' => 'hematology_anemia'])],
            ['slug' => "hematology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'easy_bruising/bleeding', 'label' => 'Easy bruising/bleeding', 'data' => 'hematology_easy_bruising/bleeding'])],
            ['slug' => "hematology", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'past_transfusions', 'label' => 'Past Transfusions', 'data' => 'hematology_past_transfusions'])],
            ['slug' => "hematology", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'hematology_notes_notes'])],

            ['slug' => "Psychiatry", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'tension/anxiety', 'label' => 'Tension/Anxiety', 'data' => 'Psychiatry_anxiety'])],
            ['slug' => "Psychiatry", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'depression/suicide_ideation', 'label' => 'Depression/suicide ideation', 'data' => 'Psychiatry_depression/suicide_ideation'])],
            ['slug' => "Psychiatry", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'memory_problems', 'label' => 'Memory problems', 'data' => 'Psychiatry_memory_problems'])],
            ['slug' => "Psychiatry", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'unusual_problems', 'label' => 'Unusual problems', 'data' => 'Psychiatry_unusual_problems'])],
            ['slug' => "Psychiatry", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'Psychiatry_notes_notes'])],

            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'abnormal_growth', 'label' => 'Abnormal growth', 'data' => 'Endocrine_abnormal_growth'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'increased_appetite', 'label' => 'Increased appetite Or Flushing', 'data' => 'Endocrine_increased_appetite'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'increased_thirst', 'label' => 'Increased thirst', 'data' => 'Endocrine_increased_thirst'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'increased_urine_production', 'label' => 'Increased urine production', 'data' => 'Endocrine_increased_urine_production'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'thyroid_trouble', 'label' => 'Thyroid trouble', 'data' => 'Endocrine_thyroid_trouble'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'heat/cold_intolerance', 'label' => 'Heat/cold intolerance', 'data' => 'Endocrine_heat/cold_intolerance'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'excessive_sweating', 'label' => 'Excessive sweating', 'data' => 'Endocrine_excessive_sweating'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'checkbox', 'name' => 'diabetes', 'label' => 'Diabetes', 'data' => 'Endocrine_diabetes'])],
            ['slug' => "Endocrine", 'attributes' => json_encode(['type' => 'textarea', 'name' => 'notes', 'label' => 'Notes', 'data' => 'Endocrine_notes_notes'])],

        ];

        $slug = ['general', 'allergy', 'skin', 'head', 'eye', 'ears', 'nose', 'mouth/throat', 'neck', 'breast', 'respiratory', 'cardiovascular', 'gastro', 'genito_urinary', 'peripheral_vascular_disease', 'musculoskeletal', 'neurology', 'skin', 'hematology', 'hematology', 'Psychiatry', 'Endocrine'];

        DB::table('dynamic_forms')->WhereIN('slug', $slug)->delete();
        DB::table('dynamic_forms')->insert($ros);

        $this->call([
            UpdateROSSeeder::class,
        ]);
    }
}
