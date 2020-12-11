<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
    		[
                'first_name' => "cureselect",
                'last_name' => "admin",
                'role_id' => 1,
                'email' => "admin@a2z.health",
                'username' => "admin@a2z.health",
                'password' => app('hash')->make('Test12345'),
                'address' => json_encode(['address' => 'kamdarnagar']),
                'timezone_id' => 1,
                'is_active' => 1,
            ]
    	];

        DB::table('users')->insert($users);
    }
}
