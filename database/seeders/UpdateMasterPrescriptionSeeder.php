<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateMasterPrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $medicine_types = [
            [
                'master_type_slug' => 'health', 
                'slug' => 'prescription', 
                'name' => 'Prescription',
                'is_active' => 1,
            ],[
                'master_type_slug' => 'health', 
                'slug' => 'prescription_glasses', 
                'name' => 'Prescription Glasses',
                'is_active' => 1,
            ],[
                'master_type_slug' => 'health', 
                'slug' => 'surgical-procedure', 
                'name' => 'Surgical Procedure',
                'is_active' => 1,
            ]
        ];

        DB::table('masters')->insertOrIgnore($medicine_types);
    }
}
