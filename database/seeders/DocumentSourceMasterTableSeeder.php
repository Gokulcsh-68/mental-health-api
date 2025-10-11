<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class DocumentSourceMasterTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$master_types = [
    		['slug' => 'document-source'],
        ];

        DB::table('master_types')->insertOrIgnore($master_types);

        $document_types = [
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'lab', 
                'name' => 'Lab Results',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'imaging', 
                'name' => 'Imaging Report',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'medical-history', 
                'name' => 'Medical History',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'identity-proof', 
                'name' => 'Identity Proof',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'address-proof', 
                'name' => 'Address Proof',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'health-insurance', 
                'name' => 'Health Insurance',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'travel-insurance', 
                'name' => 'Travel Insurance',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'allergy', 
                'name' => 'Allergy',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'disease-or-problem', 
                'name' => 'Disease or Problems',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'immunisation', 
                'name' => 'Immunisation',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'medication-prescription', 
                'name' => 'Medication Prescription',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'patient-history', 
                'name' => 'Patient History',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'patient-info', 
                'name' => 'Patient Info',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'others', 
                'name' => 'Others',
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'document-source', 
                'slug' => 'audio', 
                'name' => 'Audio',
                'is_active' => 1,
            ],
        ];

        DB::table('masters')->insertOrIgnore($document_types);

    }
}
