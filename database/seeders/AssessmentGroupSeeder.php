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
                'slug' => 'healthy-heart', 
                'name' => 'Healthy Heart',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'assessment_group', 
                'slug' => 'psychiatric-exam', 
                'name' => 'Psychiatric Exam',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'assessment_group', 
                'slug' => 'stroke-scale', 
                'name' => 'Stroke Scale',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
    	];

        DB::table('masters')->insert($assessmentGroups);
    }
}
