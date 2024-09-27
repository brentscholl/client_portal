<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\AssignService;
    use App\Http\Livewire\Admin\AssignUser;
    use App\Http\Livewire\Admin\Clients\ClientReps;
    use App\Http\Livewire\Admin\Clients\ClientRow;
    use App\Http\Livewire\Admin\Clients\Create;
    use App\Http\Livewire\Admin\Clients\Edit;
    use App\Http\Livewire\Admin\Clients\RepCard;
    use App\Http\Livewire\Admin\Clients\Show;
    use App\Models\Action;
    use App\Models\Assignee;
    use App\Models\Client;
    use App\Models\Service;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Livewire;
    use Tests\TestCase;

    class ClientTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_see_client_index_page()
        {
            $this->actingAs(User::factory()->admin()->create());
            $response = $this->get(route('admin.clients.index'));

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_create_client_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $this->get(route('admin.clients.index'))->assertSeeLivewire('admin.clients.create');

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_client()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->set('title', 'Acme Inc.')
                ->set('website_url', 'www.acme.com')
                ->set('monthly_budget', '1000')
                ->set('annual_budget', '12000')
                ->call('addClient');
            $response->assertHasNoErrors();
            $this->assertTrue(Client::where('title', 'Acme Inc.')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_client_with_avatar()
        {
            $this->actingAs(User::factory()->admin()->create());
            Storage::fake('avatars');

            $file = UploadedFile::fake()->image('some-photo.jpg');
            $response = Livewire::test(Create::class)
                ->set('title', 'Acme Inc.')
                ->set('website_url', 'www.acme.com')
                ->set('monthly_budget', '1000')
                ->set('annual_budget', '12000')
                ->set('newAvatar', $file)
                ->call('addClient');
            $response->assertHasNoErrors();
            $this->assertTrue(Client::where('title', 'Acme Inc.')
                ->where('avatar', '!=', null)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_client_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            //$this->get(route('admin.users.index'))->assertSeeLivewire('admin.users.edit');
            $this->get(route('admin.clients.show', $client->id))->assertSeeLivewire('admin.clients.edit');

            $response = Livewire::test(Edit::class, ['client' => $client])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Edit::class, ['client' => $client])
                ->call('load')
                ->set('title', 'Dunder Mifflin')
                ->call('saveClient');
            $response->assertHasNoErrors();
            $this->assertTrue(Client::where('title', 'Dunder Mifflin')->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_a_client_with_avatar()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            Storage::fake('avatars');

            $file = UploadedFile::fake()->image('some-photo.jpg');

            $response = Livewire::test(Edit::class, ['client' => $client])
                ->call('load')
                ->set('title', 'Dunder Mifflin')
                ->set('newAvatar', $file)
                ->call('saveClient');
            $response->assertHasNoErrors();
            $this->assertTrue(Client::where('title', 'Dunder Mifflin')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_action_is_created_when_an_admin_edits_a_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Edit::class, ['client' => $client])
                ->call('load')
                ->set('title', 'Dunder Mifflin')
                ->call('saveClient');
            $response->assertHasNoErrors();
            $this->assertTrue(Action::where('type', 'model_updated')->where('actionable_id', $client->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Row
            $client1 = Client::factory()->create();
            $response = Livewire::test(ClientRow::class, ['client' => $client1])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.clients.index'));
            $this->assertFalse(Client::where('id', $client1->id)->exists());

            // Show
            $client2 = Client::factory()->create();
            $response = Livewire::test(Show::class, ['client' => $client2])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.clients.index'));
            $this->assertFalse(Client::where('id', $client2->id)->exists());
        }

        /** @test */
        public function a_marketing_advisor_can_be_assigned_to_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $user = User::create()->admin()->create();
            $response = Livewire::test(AssignUser::class, ['model' => $client, 'assign_marketing_advisor' => true])
                ->call('load')
                ->call('assign', $user->id);
            $response->assertHasNoErrors();

            $this->assertTrue(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($client))
                ->where('assigneeable_id', $client->id)
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'user_assigned')->where('actionable_id', $client->id)->exists());
        }

        /** @test */
        public function a_marketing_advisor_can_be_unassigned_from_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $user = User::create()->admin()->create();
            $response = Livewire::test(AssignUser::class, ['model' => $client, 'assign_marketing_advisor' => true])
                ->call('load')
                ->call('assign', $user->id);
            $response->assertHasNoErrors();

            $this->assertTrue(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($client))
                ->where('assigneeable_id', $client->id)
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'user_assigned')->where('actionable_id', $client->id)->exists());

            // Unnassign
            $response = Livewire::test(AssignUser::class, ['model' => $client, 'assign_marketing_advisor' => true])
                ->call('load')
                ->call('unassign', $user->id);
            $response->assertHasNoErrors();

            $this->assertFalse(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($client))
                ->where('assigneeable_id', $client->id)
                ->exists());

            $this->assertFalse(Action::where('type', 'user_assigned')->where('actionable_id', $client->id)->exists());
        }
        /** @test */
        public function a_service_without_project_can_be_assigned_to_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            Service::factory()->create(['title' => 'Web Design', 'slug' => 'web-design']);
            $service = Service::factory()->create(['title' => 'Logo Design', 'slug' => 'logo-design']);
            $response = Livewire::test(AssignService::class, ['client' => $client])
                ->call('load')
                ->call('assign', $service->id)
                ->set('client', $client->fresh());
            $response->assertHasNoErrors();

            $this->assertTrue(Client::where('id', $client->id)
                ->whereHas('services')
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'service_assigned')->where('actionable_id', $client->id)->exists());

        }
        /** @test */
        public function a_service_without_project_can_be_unassigned_from_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            Service::factory()->create(['title' => 'Web Design', 'slug' => 'web-design']);
            $service = Service::factory()->create(['title' => 'Logo Design', 'slug' => 'logo-design']);
            $client->services()->attach($service->id);

            $response = Livewire::test(AssignService::class, ['client' => $client])
                ->call('load')
                ->call('unassign', $service->id)
                ->set('client', $client->fresh());
            $response->assertHasNoErrors();

            $this->assertTrue(Client::where('id', $client->id)
                ->whereDoesntHave('services')
                ->exists());

        }
    }
