<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateAssessmentStrokeScaleOrder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $forms_slug = ['stroke-scale-symptoms-signs','stroke-scale-nih','stroke-scale-tpa-contraindications','stroke-scale-reconstitution-tnkase','stroke-scale-administration-tnkase','stroke-scale-ischemic'];

        foreach ($forms_slug as $key => $value) {

        	DB::table('forms')->where('slug', $value)->update(['order'=>$key+1]);        	
        }

    }
}
