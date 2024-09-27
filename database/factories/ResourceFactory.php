<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'type' => 'text',
            'label' => $this->faker->word(),
            'tagline' => '',
            'value' => '',
        ];
    }
}
