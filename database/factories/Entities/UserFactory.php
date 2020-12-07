<?php

namespace Database\Factories\Entities;

use App\Entities\Timezone;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail;
        $timezone_id = Timezone::first()->id;
        return [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'role_id' => 1,
            'email' => $email,
            'username' => $email,
            'password' => app('hash')->make('Test123'),
            'secret' => 'secret',
            'address' => json_encode(['address' => '#78 DHJE']),
            'timezone_id' => $timezone_id,
            'is_active' => true,
        ];
    }
}
