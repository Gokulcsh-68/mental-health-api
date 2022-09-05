<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateMedicineTypeSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {          
        
    	$master_types = [
    		['slug' => 'medicineType'],
    	];
        
        DB::table('master_types')->insertOrIgnore($master_types);

        $this->dataDump();
    }

    private function dataDump()
    {

        
        DB::table('masters')->Where('master_type_slug','medicineType')->delete();
        
        $medicine_file_path = __DIR__ . '/source/medicinetype.csv';
        $medicine_file = file($medicine_file_path);
        $medicine_data_collection = array_slice($medicine_file, 1);
        
        $medicine_chunched = (array_chunk($medicine_data_collection, 1000));
        
        $i = 1;
        foreach($medicine_chunched as $meds) {
            $meds_data = [];
            
            foreach($meds as $item) {
                $slug = trim('medtype_'.$item);
                $name = trim(ucfirst($item));
                
                $meds_data[] = [
                    'master_type_slug' => 'medicineType', 
                    'slug' => $slug, 
                    'name' => $name,
                    'is_active' => 1,
                ];
            }

            DB::table('masters')->insertOrIgnore($meds_data);
        }
    }
}
