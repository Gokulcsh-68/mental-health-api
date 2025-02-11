<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateROSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entity = [
            ['slug' => "genitourinary-female"   , 'attributes' => json_encode(['type'=>'checkbox', 'name'=>'history_of_std`s', 'label'=>' history of S T D`s' , 'data'=>'genitourinary - female_history_of_std`s_notes'])],
            ['slug' => "psychiatric"    , 'attributes' => json_encode(['type'=>'checkbox', 'name'=>'OCD', 'label'=>' O C D' , 'data'=>'psychiatric_OCD_notes'])],
            ['slug' => "chest"  , 'attributes' => json_encode(['type'=>'checkbox', 'name'=>'dullness', 'label'=>'dullness' , 'data'=>'chest_or_dullness_notes'])],
            ['slug' => "throat" , 'attributes' => json_encode(['type'=>'checkbox', 'name'=>'history_of_recurrent_sore_throats_or_of_strep_throat_or_of_rheumatic_fever', 'label'=>' history of recurrent sore throats or strep throat or rheumatic fever' , 'data'=>'throat_history_of_recurrent_sore_throats_or_of_strep_throat_or_of_rheumatic_fever_notes'])],
            ['slug' => "hematologic"    , 'attributes' => json_encode(['type'=>'checkbox', 'name'=>'Edema_hemangioma_or_other', 'label'=>' Edema, hemangioma or other' , 'data'=>'hematologic_Edema_hemangioma_or_other_notes'])],
            ['slug' => "gastrointestinal"   , 'attributes' => json_encode(['type'=>'checkbox', 'name'=>'early_satiety', 'label'=>' early satiety' , 'data'=>'gastrointestinal_early_satiety_notes'])],
        ];

        $slug_data = ['genitourinary - female_history_of_std`s_notes','psychiatric_OCD_notes','chest_or_dullness_notes','throat_history_of_recurrent_sore_throats_or_of_strep_throat_or_of_rheumatic_fever_notes','hematologic_unexplained_swollen_areas_notes','gastrointestinal_early_satiety_notes'];
        
        DB::table('dynamic_forms')->WhereIN('attributes->data', $slug_data)->delete();

        DB::table('dynamic_forms')->insertOrIgnore($entity);
    }
}
