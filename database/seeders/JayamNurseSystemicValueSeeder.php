<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class JayamExamSystemicValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'jayam_nursing_sub_types'],
            [ 'slug' => 'jayam-nursing-examination']
        ];
        
        DB::table('master_types')->insertOrIgnore($master_types);


       DB::table('masters')->Where('master_type_slug','jayam_nursing_sub_types')->delete();
       DB::table('masters')->Where('master_type_slug','jayam-nursing-examination')->delete();


        $this->systemHeadDataDump();       
        $this->systemValueDataDump();      


	}



    private function systemHeadDataDump()
    {
        $sys_file_path = __DIR__ . '/source/jayam_nursingHead.csv';
        $sys_file = file($sys_file_path);
        $sys_data_collection = array_slice($sys_file, 0);
        
        $sys_chunched = (array_chunk($sys_data_collection, 1000));
        
        $i = 1;
        foreach($sys_chunched as $syss) {
            $sys_data = [];
            
            foreach($syss as $item) {
                $data = explode(',', $item);
                $slug = str_slug(trim($data[0]));
                $name = trim($data[0]);
                $gender = count($data) > 1 ?trim($data[1]):null;
                
                $sys_data[] = [
                    'master_type_slug' => 'jayam-nursing-examination', 
                    'slug' => $slug, 
                    'name' => $name,
                    'attributes' => $gender?json_encode(['gender' => $gender]):null,
                    'is_active' => 1,
                ];
            }

            DB::table('masters')->insertOrIgnore($sys_data);
        }
    }


    private function systemValueDataDump()
    {
        $sys_file_path = __DIR__ . '/source/jayam_nursingValue.csv';
        $sys_file = file($sys_file_path);
        $sys_data_collection = array_slice($sys_file, 0);
        
        $sys_chunched = (array_chunk($sys_data_collection, 1000));
        
        $i = 1;
        foreach($sys_chunched as $syss) {
            $sys_data = [];
            
            foreach($syss as $item) {
                $data = explode(',', $item);
                $masterslug = str_slug(trim($data[0]));
                $slug = str_slug(trim($data[0]).$data[1]);
                $name = trim($data[1]);

                $multiple = 'yes';
                $icon = 'no';
                if(isset($data[2])){
                    if(trim($data[2]) == 'nomultiple'){
                        $multiple = 'no';
                    }
                }
                if(isset($data[3])){
                    if(trim($data[3]) == 'icon'){
                        $icon = 'yes';
                    }
                }
                
                $sys_data[] = [
                    'master_type_slug' => 'jayam_nursing_sub_types', 
                    'slug' => $slug, 
                    'name' => $name,
                    'attributes' => json_encode(['reference_slug' => $masterslug, 'multiple'=>$multiple, 'icon'=>$icon]),
                    'is_active' => 1,
                ];
            }

            DB::table('masters')->insertOrIgnore($sys_data);
        }
    }
}