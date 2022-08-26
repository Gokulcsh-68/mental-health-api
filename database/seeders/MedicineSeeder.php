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

        $medicines = [
    		[
	    		'name' => 'Dolo',
	            'type' => "Tablets",
	            'dosage' => "650 mg",
	            'generic_name' => 'Acetaminophen or Paracetamol'
        	],[
	    		'name' => 'Paracetamol',
	            'type' => "Tablets",
	            'dosage' => "200 mg",
	            'generic_name' => 'Acetaminophen or Paracetamol'
        	],[
	    		'name' => 'Dolo',
	            'type' => "Tablets",
	            'dosage' => "250 mg",
	            'generic_name' => 'Acetaminophen or Paracetamol'
        	],[
	    		'name' => 'Dolo',
	            'type' => "Syrup",
	            'dosage' => "60 ml",
	            'generic_name' => 'Acetaminophen or Paracetamol'
        	],[
	    		'name' => 'Crocin',
	            'type' => "Tablets",
	            'dosage' => "500 mg",
	            'generic_name' => 'Paracetamol and Caffeine Tablets I.P.'
        	],[
	    		'name' => 'Crocin',
	            'type' => "Tablets",
	            'dosage' => "100 mg",
	            'generic_name' => 'Paracetamol and Caffeine Tablets I.P.'
        	],[
	    		'name' => 'Crocin',
	            'type' => "Syrup",
	            'dosage' => "15 ml",
	            'generic_name' => 'Paracetamol and Caffeine Tablets I.P.'
        	],[
	    		'name' => 'Crocin',
	            'type' => "Syrup",
	            'dosage' => "100 ml",
	            'generic_name' => 'Paracetamol and Caffeine Tablets I.P.'
        	],[
	    		'name' => 'Benadryl',
	            'type' => "Syrup",
	            'dosage' => "500 ml",
	            'generic_name' => 'Diphenhydramine'
        	],
    	];

        DB::table('medicines')->insert($medicines);
    }
}
