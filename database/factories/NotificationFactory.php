<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'user_id' => User::factory(),
            'type' => 'default',
            'notifiable_type' => 'App\Model\Task',
            'notifiable_id' => Task::factory(),
            'data' => null,
            'read_at' => null,
            'seen' => 0,
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function seen()
    {
        return [
            'seen' => 1,
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function read()
    {
        return [
            'read_at' => now(),
        ];
    }
}
