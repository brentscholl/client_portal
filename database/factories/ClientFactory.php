<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->company,
            'monthly_budget' => $this->faker->numberBetween(500, 5000),
            'annual_budget' => $this->faker->numberBetween(30000, 100000),
            'primary_contact' => null,
            'avatar' => null,
            'website_url' => $this->faker->url,
        ];
    }

    /**
     * Indicate that the client is archived
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'archived' => 1,
            ];
        });
    }
}
