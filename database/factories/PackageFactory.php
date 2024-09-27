<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Package::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'service_id' => Service::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(3)
        ];
    }
}
