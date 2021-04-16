<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('masters')->Where('master_type_slug','sig')->delete();

        DB::table('master_types')->Where('slug','sig')->delete();

        $master_types = [
            [ 'slug' => 'sig'],
        ];
        
        DB::table('master_types')->insert($master_types);


        $health_types = [
            [
                'master_type_slug' => 'sig', 
                'slug' => 'BID', 
                'name' => 'Twice a day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'TID', 
                'name' => 'Thrice a day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QID', 
                'name' => 'four times a day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'STAT', 
                'name' => 'Immediately',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QD', 
                'name' => 'Once daily',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QW', 
                'name' => 'Once weekly',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QM', 
                'name' => 'Once a month',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QH', 
                'name' => 'every hour',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'Q1H', 
                'name' => 'every one hour',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'MWF', 
                'name' => 'Monday Wednesday Friday',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'MW', 
                'name' => 'Monday Wednesday',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QAM', 
                'name' => 'Every Morning',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QPM', 
                'name' => 'Every Evening',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'AMPM', 
                'name' => 'In the morning and evening',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => '2xW', 
                'name' => 'Twice weekly',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => '3xW', 
                'name' => 'Thrice weekly',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => '4xW', 
                'name' => 'Four times weekly',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'UG', 
                'name' => 'Until Gone',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'UR', 
                'name' => 'Until received',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSQD', 
                'name' => '½ every day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QOD', 
                'name' => 'every other day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSQH', 
                'name' => '½ every hour',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSBID', 
                'name' => '½ twice a day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSQID', 
                'name' => '½ four times a day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSQAM', 
                'name' => '½ every morning',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSQPM', 
                'name' => '½ every evening',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSQOD', 
                'name' => '½ every other day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSTID', 
                'name' => '½ three times a day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'PC', 
                'name' => 'after meal',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'HS', 
                'name' => 'at bedtime',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'PCB', 
                'name' => 'after breakfast',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'PCBHS', 
                'name' => 'after breakfast and bed time',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'PCHS', 
                'name' => 'after meal and bed time',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'PCL', 
                'name' => 'after Lunch',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'SSHS', 
                'name' => '½ at bed time',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'PCD', 
                'name' => 'after dinner',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ALTD', 
                'name' => 'on alternate day',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ALTAM', 
                'name' => 'on alternate morning',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ALTPM', 
                'name' => 'on alternate evening',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'AC', 
                'name' => 'after meal',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ACHS', 
                'name' => 'before meals and at bed time',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ACB', 
                'name' => 'before breakfast',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ACL', 
                'name' => 'before lunch',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ACD', 
                'name' => 'before dinner',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'ACS', 
                'name' => 'before supper',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'D', 
                'name' => 'daily',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QOH', 
                'name' => 'every other hour',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QOW', 
                'name' => 'every other week',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ],
            [
                'master_type_slug' => 'sig', 
                'slug' => 'QOM', 
                'name' => 'every other month',
                'attributes' => json_encode([]),
                'is_active' => 1,
            ]
        ];

        DB::table('masters')->insert($health_types);
    }
}
