<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateFormsroleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        $slug_data = ['healthy-heart','psychiatric-exam-symptoms','psychiatric-exam-anger','psychiatric-exam-anxiety','psychiatric-exam-depression','psychiatric-exam-mania','stroke-scale-symptoms-signs','stroke-scale-nih','stroke-scale-tpa-contraindications','stroke-scale-ischemic', 'covid_self_assessment'];
        $roles = ["admin", "provider", "hospitalgroup", "hospital", "folio"];

        DB::table('forms')->WhereIN('slug', $slug_data)->update(['role_code'=>$roles]);

    }
}
