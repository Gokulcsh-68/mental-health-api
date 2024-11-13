<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateMedicationGenericSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $us_medicine_file_path = __DIR__ . '/source/newGenericMedication.csv';
        $us_medicine_file = file($us_medicine_file_path);
        $us_medicine_data_collection = array_slice($us_medicine_file, 1);

        $us_medicine_chunched = (array_chunk($us_medicine_data_collection, 1000));

        $i = 1;
        foreach($us_medicine_chunched as $us_meds) {
            $us_meds_data = [];

            foreach($us_meds as $k => $item) {
                $data = explode(',', $item);
                $name = trim($data[0]);

                $us_meds_data[] = [
                    'master_type_slug' => 'Generic',
                    'slug' => str_slug("GN".$k.$name),
                    'name' => $name,
                    'is_active' => 1,
                ];
            }

            DB::table('masters')->insert($us_meds_data);
        }
    }
}
