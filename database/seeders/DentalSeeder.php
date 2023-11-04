<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $master_types = [
        ['slug' => 'dental_diagnosis'],
        ['slug' => 'dental_treatment'],
      ];
        
        DB::table('master_types')->insertOrIgnore($master_types);

        $sypmtoms = [
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Dental caries', 'slug' => str_slug('dn_dg_Dental caries')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Missing tooth', 'slug' => str_slug('dn_dg_Missing tooth')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Filled teeth', 'slug' => str_slug('dn_dg_Filled teeth')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Stains', 'slug' => str_slug('dn_dg_Stains')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Calculus', 'slug' => str_slug('dn_dg_Calculus')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Attrition', 'slug' => str_slug('dn_dg_Attrition')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Abrasion', 'slug' => str_slug('dn_dg_Abrasion')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Abfraction', 'slug' => str_slug('dn_dg_Abfraction')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Erosion', 'slug' => str_slug('dn_dg_Erosion')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Grossly decayed teeth', 'slug' => str_slug('dn_dg_Grossly decayed teeth')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Root stumps', 'slug' => str_slug('dn_dg_Root stumps')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Fractures', 'slug' => str_slug('dn_dg_Fractures')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Bone Fracture (Mandi)', 'slug' => str_slug('dn_dg_Bone Fracture (Mandi)')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Bone Fracture (Max)', 'slug' => str_slug('dn_dg_Bone Fracture (Max)')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Cavities', 'slug' => str_slug('dn_dg_Cavities')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Cervical Abrasion', 'slug' => str_slug('dn_dg_Cervical Abrasion')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Dental caries with Abscess', 'slug' => str_slug('dn_dg_Dental caries with Abscess')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Dental caries with Pulpitis', 'slug' => str_slug('dn_dg_Dental caries with Pulpitis')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Dentoalveolar abscess', 'slug' => str_slug('dn_dg_Dentoalveolar abscess')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Factured restoration', 'slug' => str_slug('dn_dg_Factured restoration')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Gingivitis', 'slug' => str_slug('dn_dg_Gingivitis')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Impacted', 'slug' => str_slug('dn_dg_Impacted')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Malocclusion', 'slug' => str_slug('dn_dg_Malocclusion')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Mesiodens', 'slug' => str_slug('dn_dg_Mesiodens')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Non Vital', 'slug' => str_slug('dn_dg_Non Vital')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Partially Edentulous', 'slug' => str_slug('dn_dg_Partially Edentulous')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Pericoronitis', 'slug' => str_slug('dn_dg_Pericoronitis')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Periodontitis', 'slug' => str_slug('dn_dg_Periodontitis')],
            [ 'master_type_slug' => 'dental_diagnosis', 'name' => 'Retained Deciduous', 'slug' => str_slug('dn_dg_Retained Deciduous')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RCT', 'slug' => str_slug('dn_tr_RCT')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Extractions', 'slug' => str_slug('dn_tr_Extractions')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Scaling', 'slug' => str_slug('dn_tr_Scaling')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Filling', 'slug' => str_slug('dn_tr_Filling')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Crown & Bridge', 'slug' => str_slug('dn_tr_Crown & Bridge')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Bleaching', 'slug' => str_slug('dn_tr_Bleaching')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Orthodontic Braces', 'slug' => str_slug('dn_tr_Orthodontic Braces')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Cosmetic Procedures', 'slug' => str_slug('dn_tr_Cosmetic Procedures')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Ball Attachment', 'slug' => str_slug('dn_tr_Ball Attachment')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'CD', 'slug' => str_slug('dn_tr_CD')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD', 'slug' => str_slug('dn_tr_FPD')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD-Bridge-All Ceramic', 'slug' => str_slug('dn_tr_FPD-Bridge-All Ceramic')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD-Bridge-Metal', 'slug' => str_slug('dn_tr_FPD-Bridge-Metal')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD-Bridge-PFM', 'slug' => str_slug('dn_tr_FPD-Bridge-PFM')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD-Crown-All Ceramic', 'slug' => str_slug('dn_tr_FPD-Crown-All Ceramic')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD-Crown-Metal', 'slug' => str_slug('dn_tr_FPD-Crown-Metal')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'FPD-Crown-PFM', 'slug' => str_slug('dn_tr_FPD-Crown-PFM')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Filling (Comp.)', 'slug' => str_slug('dn_tr_Filling (Comp.)')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Filling (GIC)', 'slug' => str_slug('dn_tr_Filling (GIC)')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Filling (Temp.)', 'slug' => str_slug('dn_tr_Filling (Temp.)')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Fixed Appliance', 'slug' => str_slug('dn_tr_Fixed Appliance')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Frenectomy', 'slug' => str_slug('dn_tr_Frenectomy')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Gingival Pack', 'slug' => str_slug('dn_tr_Gingival Pack')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Gingivectomy', 'slug' => str_slug('dn_tr_Gingivectomy')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'IMF', 'slug' => str_slug('dn_tr_IMF')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Implant', 'slug' => str_slug('dn_tr_Implant')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Implant-Abutment', 'slug' => str_slug('dn_tr_Implant-Abutment')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Implant-Crown Placement', 'slug' => str_slug('dn_tr_Implant-Crown Placement')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Implant-Impression', 'slug' => str_slug('dn_tr_Implant-Impression')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Implant-Placement', 'slug' => str_slug('dn_tr_Implant-Placement')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Implant-Trial', 'slug' => str_slug('dn_tr_Implant-Trial')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'MTA', 'slug' => str_slug('dn_tr_MTA')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Medication', 'slug' => str_slug('dn_tr_Medication')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Operculectomy', 'slug' => str_slug('dn_tr_Operculectomy')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Periapical Surgery', 'slug' => str_slug('dn_tr_Periapical Surgery')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RCT-Obturation', 'slug' => str_slug('dn_tr_RCT-Obturation')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RCT-Access-Closed Dressing', 'slug' => str_slug('dn_tr_RCT-Access-Closed Dressing')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RCT-Access-Open Dressing', 'slug' => str_slug('dn_tr_RCT-Access-Open Dressing')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RCT-BMP-Closed Dressing', 'slug' => str_slug('dn_tr_RCT-BMP-Closed Dressing')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RCT-BMP-Open Dressing', 'slug' => str_slug('dn_tr_RCT-BMP-Open Dressing')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RPD', 'slug' => str_slug('dn_tr_RPD')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RPD-Bite Registration', 'slug' => str_slug('dn_tr_RPD-Bite Registration')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RPD-Impression', 'slug' => str_slug('dn_tr_RPD-Impression')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RPD-Insertion', 'slug' => str_slug('dn_tr_RPD-Insertion')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'RPD-Trial', 'slug' => str_slug('dn_tr_RPD-Trial')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Removal Appliance', 'slug' => str_slug('dn_tr_Removal Appliance')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Retainer', 'slug' => str_slug('dn_tr_Retainer')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'SRP', 'slug' => str_slug('dn_tr_SRP')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Splint', 'slug' => str_slug('dn_tr_Splint')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Surgical extraction', 'slug' => str_slug('dn_tr_Surgical extraction')],
            [ 'master_type_slug' => 'dental_treatment', 'name' => 'Veneer', 'slug' => str_slug('dn_tr_Veneer')],
    	];

        DB::table('masters')->insertOrIgnore($sypmtoms);
    }
}
