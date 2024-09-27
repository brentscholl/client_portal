<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = File::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'src' => '/',
            'file_name' => 'some-file.jpg',
            'extension' => $this->faker->fileExtension,
            'file_size' => 1000,
            'mime_type' => $this->faker->mimeType,
            'caption' => $this->faker->sentence(6, true),
            'is_resource' => false,
        ];
    }
}
