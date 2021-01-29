<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$master_types = [
    		['slug' => 'activity'],
    	];

		DB::table('master_types')->insert($master_types);
		
        $health_types = [
    		[
			'master_type_slug' => 'activity', 
			'slug' => 'aerobic', 
			'name' => 'Aerobic',
			'attributes'=> json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'basketball',
			'name' => 'basketball',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Brisk walking',
			'name' => 'Brisk walking',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Canoeing',
			'name' => 'Canoeing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Cliff Climbing',
			'name' => 'Cliff Climbing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Climbing',
			'name' => 'Climbing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'cricket',
			'name' => 'cricket',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'cycling',
			'name' => 'cycling',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'dancing',
			'name' => 'dancing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Diving',
			'name' => 'Diving',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'football',
			'name' => 'football',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'galloping',
			'name' => 'galloping',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Hiking',
			'name' => 'Hiking',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Hockey',
			'name' => 'Hockey',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Horseback riding',
			'name' => 'Horseback riding',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Ice skating',
			'name' => 'Ice skating',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Jogging',
			'name' => 'Jogging',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'jumping jacks',
			'name' => 'jumping jacks',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Jumping rope',
			'name' => 'Jumping rope',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Kayaking',
			'name' => 'Kayaking',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Meditation',
			'name' => 'Meditation',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Normal walk',
			'name' => 'Normal walk',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Paddle boating',
			'name' => 'Paddle boating',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Push-ups',
			'name' => 'Push-ups',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Rafting',
			'name' => 'Rafting',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Rollerblading',
			'name' => 'Rollerblading',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Rowing',
			'name' => 'Rowing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Rugby',
			'name' => 'Rugby',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'running',
			'name' => 'running',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'scuba diving',
			'name' => 'scuba diving',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Singing',
			'name' => 'Singing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Sit-ups',
			'name' => 'Sit-ups',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'skating',
			'name' => 'skating',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Skiing',
			'name' => 'Skiing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Skin diving',
			'name' => 'Skin diving',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Skipping with a rope',
			'name' => 'Skipping with a rope',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Snowshoeing',
			'name' => 'Snowshoeing',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'soccer',
			'name' => 'soccer',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Stair-climber',
			'name' => 'Stair-climber',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Stretching',
			'name' => 'Stretching',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Swimming',
			'name' => 'Swimming',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'tennis',
			'name' => 'tennis',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Treading water',
			'name' => 'Treading water',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Trekking',
			'name' => 'Trekking',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Trotting',
			'name' => 'Trotting',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Walking uphill',
			'name' => 'Walking uphill',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Water jogging',
			'name' => 'Water jogging',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Yoga',
			'name' => 'Yoga',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
			[
			'master_type_slug' => 'activity', 
			'slug' => 'Zumba',
			'name' => 'Zumba',
			'attributes' => json_encode([]),
			'is_active' => 1,
			],
    	];

        DB::table('masters')->insert($health_types);
    }
}
