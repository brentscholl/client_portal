<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Activities\Comment;
    use App\Http\Livewire\Admin\Activities\IndexCard;
    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Notification;
    use App\Models\Reaction;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class ActivityTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function the_activity_index_card_works()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();

            $response = Livewire::test(IndexCard::class, ['model' => $client]);

            $response->assertStatus(200);
        }

        /** @test */
        public function a_user_can_post_a_comment()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();

            $response = Livewire::test(IndexCard::class, ['model' => $client])
                ->set('newComment', 'I have something to say')
                ->call('createComment');
            $response->assertHasNoErrors();
            $this->assertTrue(Action::where('body', 'I have something to say')
                ->where('actionable_type', get_class($client))
                ->where('actionable_id', $client->id)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_user_can_mention_in_a_comment()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $user = User::factory()->create();

            $response = Livewire::test(IndexCard::class, ['model' => $client])
                ->set('newComment', '<p>This is a comment <a data-userid="'.$user->id.'">@'.$user->full_name.'</a>&nbsp;</p>')
                ->call('createComment');
            $response->assertHasNoErrors();
            $this->assertTrue(Action::where('body', '<p>This is a comment <a data-userid="'.$user->id.'">@'.$user->full_name.'</a>&nbsp;</p>')
                ->where('actionable_type', get_class($client))
                ->where('actionable_id', $client->id)
                ->exists());

            $this->assertTrue(Notification::where('type', 'App\Notifications\Mentioned')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_user_can_edit_a_comment_with_a_mention_without_notifying_the_mentioned_user_again()
        {
            $this->actingAs(User::factory()->admin()->create());

            $user = User::factory()->create();
            $client = Client::factory()->create();
            $action = Action::factory()->create([
                'body'            => '<p>This is a comment. <a class="font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100" data-userid="'.$user->id.'" href="/admin/users/'.$user->id.'" tooltip="View Mentioned User">@'.$user->full_name.'</a>&nbsp;</p>',
                'mention_ids'     => json_encode([$user->id]),
                'actionable_type' => get_class($client),
                'actionable_id'   => $client,
            ]);
            $notification = Notification::factory()->create([
                'client_id'       => null,
                'user_id'         => null,
                'type'            => 'App\Notifications\Mentioned',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $user->id,
                'data'            => '{"author":{"id":1,"name":"Destinee Schmeler"},"comment":{"id":'.$action->id.',"body":"<p>This is a comment. <a class=\"font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100\" data-userid=\"2\" href=\"\/admin\/users\/2\" tooltip=\"View Mentioned User\">@Justin Harris<\/a>&nbsp;<\/p>"},"model":{"id":3,"class":"Client","title":"Carter-Bruen"}}',
                'read_at'         => null,
                'seen'            => 0,
            ]);

            $this->assertTrue(Notification::where('type', 'App\Notifications\Mentioned')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->where('data', 'like', '%This is a comment.%')
                ->exists());

            $response = Livewire::test(Comment::class, ['action' => $action, 'model' => $client])
                ->set('comment', '<p>This is an update <a class="font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100" data-userid="'.$user->id.'" href="/admin/users/'.$user->id.'" tooltip="View Mentioned User">@'.$user->full_name.'</a>&nbsp;</p>')
                ->call('saveComment');

            $this->assertTrue(Action::where('body', '<p>This is an update <a class="font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100" data-userid="'.$user->id.'" href="/admin/users/'.$user->id.'" tooltip="View Mentioned User">@'.$user->full_name.'</a>&nbsp;</p>')
                ->exists());

            $this->assertFalse(Notification::where('type', 'App\Notifications\Mentioned')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->where('data', 'like', '%This is an update%')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_user_can_react_to_a_comment()
        {
            $this->actingAs(User::factory()->admin()->create());

            $user = User::factory()->create();
            $client = Client::factory()->create();
            $action = Action::factory()->create();

            $response = Livewire::test(Comment::class, ['action' => $action, 'model' => $client])
                ->call('react', 'thumbs_up');

            $this->assertTrue(Reaction::where('action_id', $action->id)
                ->where('type', 'thumbs_up')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_user_can_unreact_to_a_comment()
        {
            $this->actingAs(User::factory()->admin()->create());

            $user = User::factory()->create();
            $client = Client::factory()->create();
            $action = Action::factory()->create();

            $response = Livewire::test(Comment::class, ['action' => $action, 'model' => $client])
                ->call('react', 'thumbs_up');

            $this->assertTrue(Reaction::where('action_id', $action->id)
                ->where('type', 'thumbs_up')
                ->exists());

            $response = Livewire::test(Comment::class, ['action' => $action, 'model' => $client])
                ->call('unreact', 'thumbs_up');

            $this->assertFalse(Reaction::where('action_id', $action->id)
                ->where('type', 'thumbs_up')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_user_can_delete_their_comment()
        {
            $user = User::factory()->create();
            $this->actingAs($user);

            $client = Client::factory()->create();
            $action = Action::factory()->create(['user_id' => $user->id]);

            $response = Livewire::test(Comment::class, ['action' => $action, 'model' => $client])
                ->call('removeComment');

            $this->assertFalse(Action::where('id', $action->id)
                ->exists());

            $response->assertStatus(200);
        }
    }
