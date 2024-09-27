<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Files\Edit;
    use App\Http\Livewire\Admin\Files\FileCard;
    use App\Http\Livewire\Admin\Files\Index;
    use App\Http\Livewire\Admin\Files\Upload;
    use App\Models\Answer;
    use App\Models\File;
    use App\Models\Task;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Livewire;
    use Tests\TestCase;

    class FileTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_open_the_upload_file_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Upload::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_upload_a_file_to_a_task()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();
            Storage::fake('files');

            $file = UploadedFile::fake()->image('some-photo.jpg');

            $response = Livewire::test(Upload::class)
                ->set('client', $task->client)
                ->set('client_id', $task->client->id)
                ->set('model', $task)
                ->set('is_resource', false)
                ->set('files', [$file])
                ->call('uploadFiles');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'some-photo.jpg')
                ->exists());
            $this->assertTrue(Task::where('id', $task->id)
                ->whereHas('files')
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_upload_a_file_to_as_a_resource_file()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();
            Storage::fake('files');

            $file = UploadedFile::fake()->image('some-photo.jpg');

            $response = Livewire::test(Upload::class, ['model' => null, 'is_resource' => true])
                ->set('files', [$file])
                ->call('uploadFiles');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'some-photo.jpg')
                ->where('is_resource', true)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_upload_multiple_files()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();
            Storage::fake('files');

            $file1 = UploadedFile::fake()->image('some-photo.jpg');
            $file2 = UploadedFile::fake()->create('some-file.pdf', 500);

            $response = Livewire::test(Upload::class)
                ->set('client', $task->client)
                ->set('client_id', $task->client->id)
                ->set('model', $task)
                ->set('is_resource', false)
                ->set('files', [$file1, $file2])
                ->call('uploadFiles');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'some-photo.jpg')
                ->exists());
            $this->assertTrue(File::where('src', 'some-file.pdf')
                ->exists());
            $this->assertTrue(Task::where('id', $task->id)
                ->whereHas('files')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_file_from_file_card()
        {
            $this->actingAs(User::factory()->admin()->create());

            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
            ]);

            $response = Livewire::test(FileCard::class, ['file' => $file])
                ->call('removeFile', $file->id);

            $this->assertFalse(File::where('id', $file->id)->exists());

            Storage::disk('files')->assertMissing('some-photo.jpg');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_edit_file_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Edit::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_a_file()
        {
            $this->actingAs(User::factory()->admin()->create());
            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
            ]);

            $task = Task::factory()->create();
            $task->files()->attach($file);
            $file = $file->fresh();

            $response = Livewire::test(Edit::class, ['file' => $file])
                ->call('load')
                ->set('src', 'a-new-file-name.jpg')
                ->set('caption', 'Some caption')
                ->call('saveFile');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'a-new-file-name.jpg')
                ->where('caption', 'Some caption')->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_a_file_to_task()
        {
            $this->actingAs(User::factory()->admin()->create());
            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
            ]);

            $answer = Answer::factory()->create();
            $task = Task::factory()->create();
            $answer->files()->attach($file);
            $file = $file->fresh();

            $response = Livewire::test(Edit::class, ['file' => $file])
                ->call('load')
                ->set('assign_type', 'task')
                ->call('setClient', $task->client->id)
                ->call('setProject', $task->project->id)
                ->call('setPhase', $task->phase->id)
                ->call('setTask', $task->id)
                ->set('src', 'a-new-file-name.jpg')
                ->set('caption', 'Some caption')
                ->call('saveFile');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'a-new-file-name.jpg')
                ->where('caption', 'Some caption')
                ->whereDoesntHave('answers')
                ->whereHas('tasks')
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_a_file_to_answer()
        {
            $this->actingAs(User::factory()->admin()->create());
            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
            ]);

            $answer = Answer::factory()->create();
            $task = Task::factory()->create();
            $task->files()->attach($file);
            $file = $file->fresh();

            $response = Livewire::test(Edit::class, ['file' => $file])
                ->call('load')
                ->set('assign_type', 'answer')
                ->call('setClient', $answer->client->id)
                ->call('setAnswer', $answer->id)
                ->set('src', 'a-new-file-name.jpg')
                ->set('caption', 'Some caption')
                ->call('saveFile');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'a-new-file-name.jpg')
                ->where('caption', 'Some caption')
                ->whereDoesntHave('tasks')
                ->whereHas('answers')
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_a_file_to_resource()
        {
            $this->actingAs(User::factory()->admin()->create());
            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
            ]);

            $task = Task::factory()->create();
            $task->files()->attach($file);
            $file = $file->fresh();

            $response = Livewire::test(Edit::class, ['file' => $file])
                ->call('load')
                ->set('assign_type', 'resource')
                ->set('src', 'a-new-file-name.jpg')
                ->set('caption', 'Some caption')
                ->call('saveFile');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('src', 'a-new-file-name.jpg')
                ->where('caption', 'Some caption')
                ->whereDoesntHave('tasks')
                ->where('is_resource', true)
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_the_description_from_index_page()
        {
            $this->actingAs(User::factory()->admin()->create());
            Storage::fake('files');

            $uploadedFile = UploadedFile::fake()->image('some-photo.jpg');

            Storage::disk('files')->putFileAs('/', $uploadedFile, 'some-photo.jpg');

            Storage::disk('files')->assertExists('some-photo.jpg');

            $file = File::factory()->create([
                'src' => 'some-photo.jpg',
                'file_name' => 'some-photo.jpg',
            ]);

            $task = Task::factory()->create();
            $task->files()->attach($file);
            $file = $file->fresh();

            $response = Livewire::test(Index::class)
                ->call('selectFile', $file)
                ->set('caption', 'Some caption')
                ->call('saveDescription');
            $response->assertHasNoErrors();
            $this->assertTrue(File::where('caption', 'Some caption')
                ->exists());

            $response->assertStatus(200);
        }
    }
