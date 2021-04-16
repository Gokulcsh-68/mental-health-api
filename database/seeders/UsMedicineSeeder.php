<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UsMedicineSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {  
        
        DB::table('masters')->Where('master_type_slug','medicine-us')->delete();
        
        DB::table('master_types')->Where('slug','medicine-us')->delete();
        
        $master_types = [
            [ 'slug' => 'medicine-us'],
        ];
        
        DB::table('master_types')->insert($master_types);

        $this->dataDump();
    }

    private function dataDump()
    {
        $us_medicine_file_path = __DIR__ . '/source/us_medicine.csv';
        $us_medicine_file = file($us_medicine_file_path);
        $us_medicine_data_collection = array_slice($us_medicine_file, 1);
        
        $us_medicine_chunched = (array_chunk($us_medicine_data_collection, 1000));
        
        $i = 1;
        foreach($us_medicine_chunched as $us_meds) {
            $us_meds_data = [];
            
            foreach($us_meds as $item) {
                $data = explode(',', $item);
                $slug = trim($data[0]);
                $name = trim($data[1]);
                
                $us_meds_data[] = [
                    'master_type_slug' => 'medicine-us', 
                    'slug' => $slug, 
                    'name' => $name,
                    'is_active' => 1,
                ];
            }

            DB::table('masters')->insert($us_meds_data);
        }
    }
}
