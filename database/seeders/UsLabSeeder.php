<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class UsLabSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {  
        
        DB::table('masters')->Where('master_type_slug','lab-us')->delete();
        
        DB::table('master_types')->Where('slug','lab-us')->delete();
        
        $master_types = [
            [ 'slug' => 'lab-us'],
        ];
        
        DB::table('master_types')->insert($master_types);
        
        $this->dataDump();
    }
    
    private function dataDump()
    {
        $us_lab_file_path = __DIR__ . '/source/us_lab.csv';
        $us_lab_file = file($us_lab_file_path);
        $us_lab_data_collection = array_slice($us_lab_file, 1);
        
        $us_lab_chunched = (array_chunk($us_lab_data_collection, 1000));
        
        $i = 1;
        foreach($us_lab_chunched as $us_labs) {
            $us_labs_data = [];
            
            foreach($us_labs as $item) {
                try {
                    $data = explode(',', $item);
                    $slug = trim($data[0]);
                    $name = trim($data[1]);
                    
                    $us_labs_data[] = [
                        'master_type_slug' => 'lab-us', 
                        'slug' => $slug, 
                        'name' => $name,
                        'is_active' => 1,
                    ];
                    
                } catch(\Exception $e) {
                    Log::error('Lab Import', ['error' => $e->getMessage(), 'row' => $item]);
                }
                
            }
            
            DB::table('masters')->insert($us_labs_data);
        }
    }
}
