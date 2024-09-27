<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

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
            'src' => '/',
            'filename' => $this->faker->image('public/images',640,480, null, false),
            'extension' => 'jpg',
            'file_size' => 1000,
            'mime_type' => '',
            'caption' => $this->faker->sentence(6, true),
        ];
    }
}
