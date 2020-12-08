<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class AssessmentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assessmentGroups = [
    		[
                'master_type_slug' => 'assessment_group', 
                'slug' => 'Heart', 
                'name' => 'healthy-heart',
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'assessment_group', 
                'slug' => 'Psychiatric', 
                'name' => 'psychiatric-exam',
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'assessment_group', 
                'slug' => 'Stroke Scale', 
                'name' => 'stroke-scale',
                'is_active' => 1,
            ],
    	];

        DB::table('masters')->insert($assessmentGroups);
    }
}
