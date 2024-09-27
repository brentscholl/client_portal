<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'body' => $this->faker->sentence(6, true),
            'type' => 'detail',
            'choices' => null,
            'add_file_uploader' => null,
            'order' => null,
            'client_id' => null,
        ];
    }

    /**
     * Define the model's unique state.
     *
     * @return array
     */
    public function boolean()
    {
        return [
            'type' => 'boolean',
        ];
    }

    /**
     * Define the model's select state.
     *
     * @return array
     */
    public function select()
    {
        return [
            'type' => 'select',
        ];
    }

    /**
     * Define the model's multiple state.
     *
     * @return array
     */
    public function multiple()
    {
        return [
            'type' => 'multiple',
        ];
    }
}
