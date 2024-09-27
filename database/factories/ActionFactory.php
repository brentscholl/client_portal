<?php

namespace Database\Factories;

use App\Models\Action;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Action::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        return [
            'client_id' => $client->id,
            'user_id' => User::factory(),
            'type' => 'comment',
            'value' => null,
            'relation_id' => null,
            'data' => null,
            'body' => '<p>This is a comment <a class="font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100" data-userid="'.$user->id.'" href="/admin/users/'.$user->id.'" tooltip="View Mentioned User">@'.$user->full_name.'</a>&nbsp;</p>',
            'mention_ids' => json_encode([$user->id]),
            'actionable_type' => get_class($client),
            'actionable_id' => $client,
        ];
    }
}
