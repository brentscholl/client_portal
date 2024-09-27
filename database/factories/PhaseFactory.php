<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Phase;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Phase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'project_id' => Project::factory(),
            'title' => $this->faker->numberBetween(1, 4),
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
