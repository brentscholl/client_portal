<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientAnalytic;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientAnalyticFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClientAnalytic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'analytic_link' => $this->faker->url,
        ];
    }
}
