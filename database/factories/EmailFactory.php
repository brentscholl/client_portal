<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Email;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Email::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'email_template_id' => EmailTemplate::factory(),
            'subject' => $this->faker->sentence,
            'recipients' => json_encode([$this->faker->email()]),
            'data' => '{"data": [], "user": {"fullname": "' . $this->faker->firstName() . ' ' . $this->faker->lastName() .'", "position": "' . $this->faker->title . '", "avatarUrl": "https://www.gravatar.com/avatar/9979c99545dbe3d2478a5c844d26f0e6"}, "layout": [{"title": "Message", "inputs": {"message": ""}, "layout": "message"}], "email_signature": "user"}',
            'sent_date' => now()
        ];
    }
}
