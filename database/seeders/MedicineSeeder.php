<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $medicine_file_path = __DIR__ . '/source/medicines.csv';
        $medicine_file = file($medicine_file_path);
        $medicine_data_collection = array_slice($medicine_file, 1);
        
        $medicine_chunched = (array_chunk($medicine_data_collection, 1000));
        
        $i = 1;
        foreach($medicine_chunched as $meds) {
            $meds_data = [];
            
            foreach($meds as $item) {
                $data = explode('**', $item);
                $name = trim(ucfirst($data[0]));
                $type = trim(ucfirst($data[1]));
                $dosage = trim(ucfirst($data[2]));
                
                $meds_data[] = [
		    		'name' => $name,
		            'type' => $type,
		            'dosage' => $dosage,
                ];
            }

            DB::table('medicines')->insertOrIgnore($meds_data);
        }

    }
}
