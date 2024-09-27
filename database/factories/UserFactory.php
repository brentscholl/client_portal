<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('St34lth!'), // St34lth!
            'phone' => $this->faker->phoneNumber,
            'position' => $this->faker->title,
            'avatar' => null,
            'client_id' => Client::factory(),
            'role' => 'admin',
            'remember_token' => Str::random(10),
        ];
    }
    /**
     * Define the model's in admin state.
     *
     * @return array
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'client_id' => null,
            ];
        });
    }
}
