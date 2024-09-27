<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Project;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4, true),
            'description' => $this->faker->paragraph(3, true),
            'status' => 'pending',
            'client_id' => Client::factory(),
            'service_id' => rand(1,8),
            'visible' => $this->faker->boolean,
            'due_date' => Carbon::now()->addWeeks(3),
            'completed_at' => null,
        ];
    }

    /**
     * Define the model's in progress state.
     *
     * @return array
     */
    public function inProgress()
    {
        return [
            'status' => 'inProgress',
        ];
    }

    /**
     * Define the model's on hold state.
     *
     * @return array
     */
    public function onHold()
    {
        return [
            'status' => 'hold',
        ];
    }

    /**
     * Define the model's canceled state.
     *
     * @return array
     */
    public function canceled()
    {
        return [
            'status' => 'canceled',
        ];
    }

    /**
     * Define the model's completed state.
     *
     * @return array
     */
    public function completed()
    {
        return [
            'status' => 'completed',
            'completed_at' => now(),
        ];
    }

    /**
     * Define the model's awaiting approval state.
     *
     * @return array
     */
    public function awaitingApproval()
    {
        return [
            'status' => 'awatingApproval',
        ];
    }

    /**
     * Define the model's archived state.
     *
     * @return array
     */
    public function archived()
    {
        return [
            'archived' => 1,
        ];
    }
    /**
     * Define the model's visible state.
     *
     * @return array
     */
    public function hidden()
    {
        return $this->state(function (array $attributes) {
            return [
                'visible' => 0,
            ];
        });
    }
    /**
     * Define the model's visble state.
     *
     * @return array
     */
    public function visible()
    {
        return $this->state(function (array $attributes) {
            return [
                'visible' => 1,
            ];
        });
    }
}
