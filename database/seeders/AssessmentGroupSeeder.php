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
                'master_type_slug' => 'assessment-group', 
                'slug' => 'healthy-heart', 
                'name' => 'Healthy Heart',
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'assessment-group', 
                'slug' => 'psychiatric-exam', 
                'name' => 'Psychiatric Exam',
                'is_active' => 1,
            ],
    		[
                'master_type_slug' => 'assessment-group', 
                'slug' => 'stroke-scale', 
                'name' => 'Stroke Scale',
                'is_active' => 1,
            ],
    	];

        DB::table('masters')->insert($assessmentGroups);
    }
}
