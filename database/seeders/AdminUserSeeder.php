<?php

namespace Database\Seeders;

use App\Entities\User;
use DB;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'first_name' => "CureSelect",
            'last_name' => "Admin",
            'role_id' => 3,
            'email' => "support@a2z.health",
            'isd_code' => "+91",
            'mobile' => "9898989898",
            'gender' => "Male",
            'username' => "admin",
            'password' => 'Test12345',
            'address' => ['address' => 'kamdarnagar'],
            'timezone_id' => 1,
            'is_active' => 1,
    	];

        User::create($users);

    }
}
