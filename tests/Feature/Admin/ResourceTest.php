<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Resources\Create;
    use App\Http\Livewire\Admin\Resources\Edit;
    use App\Http\Livewire\Admin\Resources\ResourceCard;
    use App\Models\Action;
    use App\Models\Client;
    use App\Models\File;
    use App\Models\Project;
    use App\Models\Resource;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Livewire;
    use Tests\TestCase;

    class ResourceTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_open_the_create_resource_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_resource_for_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class)
                ->set('type', 'text')
                ->set('label', 'Trailer Park Supervisor')
                ->set('tagline', 'Sunnyvale')
                ->set('value', 'Jim Lahey')
                ->set('model', $client)
                ->call('createResource');
            $response->assertHasNoErrors();
            $this->assertTrue(Resource::where('label', 'Trailer Park Supervisor')->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_upload_a_file_to_a_resource()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            Storage::fake('files');

            $file = UploadedFile::fake()->image('some-photo.jpg');

            $response = Livewire::test(Create::class)
                ->set('type', 'file')
                ->set('label', 'Trailer Park Supervisor')
                ->set('uploaded_file', $file)
                ->set('model', $client)
                ->call('createResource');
            $response->assertHasNoErrors();
            $this->assertTrue(Resource::where('label', 'Trailer Park Supervisor')
                ->whereHas('files')->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_select_a_file_for_resource()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
                'is_resource' => true,
            ]);

            $response = Livewire::test(Create::class)
                ->set('type', 'file')
                ->set('label', 'Trailer Park Supervisor')
                ->call('assignFile', $file->id)
                ->set('model', $client)
                ->call('createResource');
            $response->assertHasNoErrors();
            $this->assertTrue(Resource::where('label', 'Trailer Park Supervisor')
                ->whereHas('files')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_resource_for_project()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();

            $response = Livewire::test(Create::class)
                ->set('type', 'text')
                ->set('label', 'Trailer Park Supervisor')
                ->set('tagline', 'Sunnyvale')
                ->set('value', 'Jim Lahey')
                ->set('model', $project)
                ->call('createResource');
            $response->assertHasNoErrors();
            $this->assertTrue(Resource::where('label', 'Trailer Park Supervisor')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_action_is_created_when_an_admin_creates_a_resource()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class)
                ->set('type', 'text')
                ->set('label', 'Trailer Park Supervisor')
                ->set('tagline', 'Sunnyvale')
                ->set('value', 'Jim Lahey')
                ->set('model', $client)
                ->call('createResource');

            $this->assertTrue(Action::where('type', 'resource_updated')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_resource_value_can_be_updated()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $resource = Resource::factory()->create(['client_id' => $client->id]);

            $response = Livewire::test(ResourceCard::class, ['resource' => $resource, 'model' => $client])
                ->set('value', 'Randy Bobandy')
                ->call('updateValue');

            $response->assertHasNoErrors();
            $this->assertTrue(Resource::where('value', 'Randy Bobandy')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function a_resource_can_be_deleted()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $resource = Resource::factory()->create(['client_id' => $client->id]);

            $response = Livewire::test(ResourceCard::class, ['resource' => $resource, 'model' => $client])
                ->call('destroy');

            $response->assertHasNoErrors();
            $this->assertFalse(Resource::where('id', $resource->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_resource_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $resource = Resource::factory()->create();

            $response = Livewire::test(Edit::class, ['resource' => $resource])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_resource()
        {
            $this->actingAs(User::factory()->admin()->create());
            $resource = Resource::factory()->create();
            $client = Client::factory()->create();

            $response = Livewire::test(Edit::class, ['resource' => $resource, 'model' => $client])
                ->call('load')
                ->set('label', 'Weekend Trailer Park Supervisor')
                ->call('saveResource');
            $response->assertHasNoErrors();
            $this->assertTrue(Resource::where('label', 'Weekend Trailer Park Supervisor')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_action_is_created_when_an_admin_updates_a_resource_for_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Edit::class)
                ->set('type', 'text')
                ->set('label', 'Weekend Trailer Park Supervisor')
                ->set('model', $client)
                ->call('saveResource');

            $this->assertTrue(Action::where('type', 'resource_updated')->exists());

            $response->assertStatus(200);
        }
    }
