<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class VdxDeleteOldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chk = DB::table('masters')->where('master_type_slug','vdx')->where('slug','vdx_duplicate_status')->get()->toArray();
        
        if(empty($chk)){
            $getVdxSub =  DB::table('masters')->where('master_type_slug','vdx_sub_types')->pluck('slug');

          foreach ($getVdxSub as $key => $value) {
              DB::table('patient_health')->where('slug',$value)->delete();
          }

          DB::table('masters')->where('master_type_slug','vdx_sub_types')->delete();

         $vdx = [
                ['master_type_slug' => 'vdx', 'name' =>'vdx_duplicate_status', 'slug' =>'vdx_duplicate_status', 'is_active' => 1]
            ];

            DB::table('masters')->insertOrIgnore($vdx);
        }
    }
}
