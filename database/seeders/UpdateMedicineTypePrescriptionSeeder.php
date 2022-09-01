<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateMedicineTypePrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('masters')->Where('master_type_slug','medicineType')->delete();

        // DB::table('master_types')->Where('slug','medicineType')->delete();

    	$master_types = [
    		['slug' => 'medicineType'],
    	];
        
        DB::table('master_types')->insertOrIgnore($master_types);



        $medicine_types = [
            [
                'master_type_slug' => 'medicineType', 
                'slug' => 'medtype_tablets', 
                'name' => 'Tablets',
                'is_active' => 1,
            ],[
                'master_type_slug' => 'medicineType', 
                'slug' => 'medtype_syrup', 
                'name' => 'Syrup',
                'is_active' => 1,
            ],[
                'master_type_slug' => 'health', 
                'slug' => 'prescription', 
                'name' => 'Prescription',
                'is_active' => 1,
            ],[
                'master_type_slug' => 'health', 
                'slug' => 'prescription_glasses', 
                'name' => 'Prescription Glasses',
                'is_active' => 1,
            ]
        ];

        DB::table('masters')->insertOrIgnore($medicine_types);
    }
}
