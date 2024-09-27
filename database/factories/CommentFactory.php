<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'body' => $this->faker->sentence(20, true),
            'commentable_type' => 'App\Models\Task',
            'commentable_id' => Task::factory(),
        ];
    }
}
