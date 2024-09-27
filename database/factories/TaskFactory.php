<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

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
            'phase_id' => Phase::factory(),
            'title' => $this->faker->sentence(5, true),
            'description' => $this->faker->sentences(5, true),
            'due_date' => $this->faker->dateTimeThisYear(),
            'type' => $this->faker->randomElement(['detail', 'approval']),
            'add_file_uploader' => $this->faker->boolean(),
            'dependable_task_id' => null,
            'priority' => $this->faker->randomElement(['1', '2']),
            'body' => $this->faker->paragraph(3, true),
            'approved' => null,
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'on-hold', 'canceled', 'completed']),
            'visible' => $this->faker->boolean,
            'completed_at' => null,
        ];
    }

    /**
     * Define the model's dependable state.
     *
     * @return array
     */
    public function dependable()
    {
        return [
            'dependable_task_id' => Task::factory(),
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
     * Define the model's admin state.
     *
     * @return array
     */
    public function admin()
    {
        return [
            'type' => 'admin',
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
