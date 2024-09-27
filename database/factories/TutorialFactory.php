<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Tutorial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TutorialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tutorial::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(6, true),
            'body' => $this->faker->paragraph(3, false),
            'image_id' => Image::factory(),
            'video_url' => $this->faker->url,
        ];
    }
}
