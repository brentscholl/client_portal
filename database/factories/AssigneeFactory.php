<?php

namespace Database\Factories;

use App\Models\Assignee;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssigneeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assignee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => null,
            'assigneeable_type' => null,
            'assigneeable_id' => null,
        ];
    }
}
