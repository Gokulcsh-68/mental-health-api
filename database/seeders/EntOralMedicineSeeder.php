<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class EntOralMedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $vdx_ent_oral_medicine = [];
        $vdx_ent_oral_medicine_file_path = __DIR__ . '/source/vdx_ent_oral_medicine.csv';
       
        $vdx_ent_oral_medicine_file = file($vdx_ent_oral_medicine_file_path);

        $vdx_ent_oral_medicine_data_collection = array_slice($vdx_ent_oral_medicine_file, 1);
         
        $vdx_ent_oral_medicine_chunched = (array_chunk($vdx_ent_oral_medicine_data_collection, 1000));
        $i = 1;

        $rand_key = 'entoral';
        // $rand_key = \Illuminate\Support\Str::random(6);

        foreach($vdx_ent_oral_medicine_chunched as $vdxs) {
            $vdx_ent_oral_medicine_data = [];
            
            foreach($vdxs as $item) {
                $data = explode(',', $item);
                $master_type_slug = trim($data[0]);


                if(!empty($data[1])){
                    $name = trim($data[1]);
                    $slug_string = str_replace(' ', '_', $name);                
                    $rmv_slug_string = preg_replace('/[^A-Za-z0-9\-]/', '', $slug_string);            
                    $slug = strtolower($rand_key.'1'.$rmv_slug_string);

                    $main_name = trim(substr($rmv_slug_string, 0,3)).trim(substr($rmv_slug_string, -5));

                    $slug = substr($slug,0,44); 
                    
                    if(!empty($name)){
                        $vdx_ent_oral_medicine_data[] = [
                            'attributes' => json_encode([
                                'reference_slug' => $master_type_slug
                            ]), 
                            'master_type_slug' => 'vdx_sub_types',
                            'slug' => $slug, 
                            'name' => $name,
                            'is_active' => 1,
                        ];
                    }

                    if(!empty($data[2])){
                        $name2 = trim($data[2]);
                        $slug_string2 = str_replace(' ', '_', $name2);                
                        $slug2 = strtolower($rand_key.'2'.$main_name.preg_replace('/[^A-Za-z0-9\-]/', '', $slug_string2));

                    $slug2 = substr($slug2,0,44);

                    if(!empty($name2)){
                        $vdx_ent_oral_medicine_data[] = [
                            'attributes' => json_encode([
                                'reference_slug' => $slug
                            ]), 
                            'master_type_slug' => 'vdx_sub_types',
                            'slug' => $slug2, 
                            'name' => $name2,
                            'is_active' => 1,
                        ]; 
                        }               



                        if(!empty($data[3])){
                            $name3 = trim($data[3]);
                            $slug_string3 = str_replace(' ', '_', $name3);                
                            $slug3 = strtolower($rand_key.'3'.$main_name.preg_replace('/[^A-Za-z0-9\-]/', '', $slug_string3));

                    $slug3 = substr($slug3,0,44);

                    if(!empty($name3)){
                            $vdx_ent_oral_medicine_data[] = [
                                'attributes' => json_encode([
                                    'reference_slug' => $slug2
                                ]),  
                                'master_type_slug' => 'vdx_sub_types',
                                'slug' => $slug3, 
                                'name' => $name3,
                                'is_active' => 1,
                            ];
                            }


                            if(!empty($data[4])){
                                $name4 = trim($data[4]);
                                $slug_string4 = str_replace(' ', '_', $name4);                
                                $slug4 = strtolower($rand_key.'4'.$main_name.preg_replace('/[^A-Za-z0-9\-]/', '', $slug_string4));

                    $slug4 = substr($slug4,0,44);

                    if(!empty($name4)){
                                $vdx_ent_oral_medicine_data[] = [
                                    'attributes' => json_encode([
                                        'reference_slug' => $slug3
                                    ]), 
                                    'master_type_slug' => 'vdx_sub_types',
                                    'slug' => $slug4, 
                                    'name' => $name4,
                                    'is_active' => 1,
                                ];
                            }
                            }
                        }


                    }
                }

            }

            DB::table('masters')->insertOrIgnore($vdx_ent_oral_medicine_data);
        }
        
    }
}
