<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Task;
use App\Models\TaskUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskUrlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskUrl::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'task_id' => Task::factory(),
            'label' => $this->faker->sentence(3),
            'url' => $this->faker->url(),
        ];
    }
}
