<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class HPILocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            ['slug' => 'hpi_location'],
        ];

        DB::table('masters')
            ->whereIn('master_type_slug', array_values(($master_types)) )
            ->delete();
        
        DB::table('master_types')
            ->whereIn('slug', array_values($master_types) )
            ->delete();

        DB::table('master_types')->insert($master_types);

        $hpi_location = [
    		['master_type_slug' => 'hpi_location', 'slug' => 'hpi_head', 'name' => 'Head', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_forehead', 'name' => 'Forehead', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_temples', 'name' => 'Temples', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_eyes', 'name' => 'Eyes', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_eyelids', 'name' => 'Eyelids', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_sclera', 'name' => 'Sclera', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_conjunctiva', 'name' => 'Conjunctiva', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_ears', 'name' => 'Ears', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_outer-ear', 'name' => 'Outer ear', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_middle-ear', 'name' => 'Middle ear', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_inner-ear', 'name' => 'Inner ear', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_nose', 'name' => 'Nose', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_nostrils', 'name' => 'Nostrils', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_nasal-passages', 'name' => 'Nasal passages', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_sinuses', 'name' => 'Sinuses', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_cheeks', 'name' => 'Cheeks', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_mouth', 'name' => 'Mouth', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_lips', 'name' => 'Lips', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_gums', 'name' => 'Gums', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_teeth', 'name' => 'Teeth', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_tongue', 'name' => 'Tongue', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_roof-of-mouth', 'name' => 'Roof of mouth', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_throat', 'name' => 'Throat', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_jaw', 'name' => 'Jaw', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_upper-jaw', 'name' => 'Upper jaw', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_lower-jaw', 'name' => 'Lower jaw', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_tmj', 'name' => 'TMJ (temporomandibular joint)', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_neck', 'name' => 'Neck', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_anterior-neck', 'name' => 'Anterior neck', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_posterior-neck', 'name' => 'Posterior neck', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_sides-of-neck', 'name' => 'Sides of neck', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_cervical-spine', 'name' => 'Cervical spine', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_chest', 'name' => 'Chest', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_sternum', 'name' => 'Sternum', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_ribs', 'name' => 'Ribs', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_breasts', 'name' => 'Breasts', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-breast', 'name' => 'Left breast', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-breast', 'name' => 'Right breast', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_axilla', 'name' => 'Axilla (armpit)', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_upper-back', 'name' => 'Upper back', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_scapula', 'name' => 'Scapula', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_shoulder-blades', 'name' => 'Shoulder blades', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_mid-back', 'name' => 'Mid back', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_lower-back', 'name' => 'Lower back', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_epigastric-region', 'name' => 'Epigastric region', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_umbilical-region', 'name' => 'Umbilical region', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_hypogastric-region', 'name' => 'Hypogastric region', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-upper-quadrant', 'name' => 'Right upper quadrant', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_liver', 'name' => 'Liver', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_gallbladder', 'name' => 'Gallbladder', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-upper-quadrant', 'name' => 'Left upper quadrant', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_stomach', 'name' => 'Stomach', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_spleen', 'name' => 'Spleen', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-lower-quadrant', 'name' => 'Right lower quadrant', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_appendix', 'name' => 'Appendix', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-ovary', 'name' => 'Right ovary', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-lower-quadrant', 'name' => 'Left lower quadrant', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-ovary', 'name' => 'Left ovary', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_sigmoid-colon', 'name' => 'Sigmoid colon', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_groin', 'name' => 'Groin', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_inguinal-region', 'name' => 'Inguinal region', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_pelvis', 'name' => 'Pelvis', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_genitals', 'name' => 'Genitals', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_external-genitalia', 'name' => 'External genitalia', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_internal-structures', 'name' => 'Internal structures', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_perineum', 'name' => 'Perineum', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_shoulders', 'name' => 'Shoulders', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-shoulder', 'name' => 'Left shoulder', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-shoulder', 'name' => 'Right shoulder', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_upper-arm', 'name' => 'Upper arm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-upper-arm', 'name' => 'Left upper arm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-upper-arm', 'name' => 'Right upper arm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_elbow', 'name' => 'Elbow', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-elbow', 'name' => 'Left elbow', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-elbow', 'name' => 'Right elbow', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_forearm', 'name' => 'Forearm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-forearm', 'name' => 'Left forearm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-forearm', 'name' => 'Right forearm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_wrist', 'name' => 'Wrist', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-wrist', 'name' => 'Left wrist', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-wrist', 'name' => 'Right wrist', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_hand', 'name' => 'Hand', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_palm', 'name' => 'Palm', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_fingers', 'name' => 'Fingers', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_nails', 'name' => 'Nails', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_thigh', 'name' => 'Thigh', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-thigh', 'name' => 'Left thigh', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-thigh', 'name' => 'Right thigh', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_knee', 'name' => 'Knee', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-knee', 'name' => 'Left knee', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-knee', 'name' => 'Right knee', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_leg', 'name' => 'Leg', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_calf', 'name' => 'Calf', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_shin', 'name' => 'Shin', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_ankle', 'name' => 'Ankle', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_left-ankle', 'name' => 'Left ankle', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_right-ankle', 'name' => 'Right ankle', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_foot', 'name' => 'Foot', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_heel', 'name' => 'Heel', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_arch', 'name' => 'Arch', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_toes', 'name' => 'Toes', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_skin', 'name' => 'Skin', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_general-rashes', 'name' => 'General rashes', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_lesions', 'name' => 'Lesions', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_pain', 'name' => 'Pain', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_muscles', 'name' => 'Muscles', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_nerves', 'name' => 'Nerves', 'is_active' => 1,],
            ['master_type_slug' => 'hpi_location', 'slug' => 'hpi_joints', 'name' => 'Joints', 'is_active' => 1,],
        ];

        DB::table('masters')->insertOrIgnore($hpi_location);
    }
}
