<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class UpdateICDCodeSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {

        DB::table('masters')->Where('master_type_slug','icd')->delete();

        $this->icd10DataDump();
    }

    private function icd10DataDump()
    {
        $icd10_file_path = __DIR__ . '/source/icd10newCode.csv';
        $icd10_file = file($icd10_file_path);
        $icd10_data_collection = array_slice($icd10_file, 1);

        $icd10_chunched = (array_chunk($icd10_data_collection, 1000));

        $i = 1;
        foreach($icd10_chunched as $icds) {
            $icd10_data = [];

            foreach($icds as $k => $item) {
                $data = explode('***', $item);
                $slug = trim($data[0]);
                $name = trim($data[1]);

                $icd10_data[] = [
                    'master_type_slug' => 'icd',
                    'slug' => $slug,
                    'name' => $name,
                    'is_active' => 1,
                ];
            }

            DB::table('masters')->insert($icd10_data);
        }
    }
}
