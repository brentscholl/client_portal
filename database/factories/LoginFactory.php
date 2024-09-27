<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Login;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoginFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Login::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomDateTime = $this->faker->dateTimeBetween('-6 hours', 'now');

        return [
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'created_at' => $randomDateTime,
            'updated_at' => $randomDateTime,
        ];
    }
}
