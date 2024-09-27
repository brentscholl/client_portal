<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\StatusChanger;
    use App\Http\Livewire\Admin\Tasks\Create;
    use App\Http\Livewire\Admin\Tasks\Edit;
    use App\Http\Livewire\Admin\Tasks\Show;
    use App\Http\Livewire\Admin\Tasks\ShowAll;
    use App\Http\Livewire\Admin\Tasks\TaskCard;
    use App\Http\Livewire\Admin\Tasks\TaskRow;
    use App\Models\Action;
    use App\Models\Phase;
    use App\Models\Task;
    use App\Models\Team;
    use App\Models\Url;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class TaskTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_see_task_index_page()
        {
            $this->actingAs(User::factory()->admin()->create());
            $response = Livewire::test(ShowAll::class);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_create_task_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $this->get(route('admin.tasks.index'))->assertSeeLivewire('admin.tasks.create');

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_task_with_minimum_details()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->create();
            $project = $phase->project;
            $client = $project->client;
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Carry ring to Mordor')
                ->call('setClient', $client->id)
                ->call('setProject', $project->id)
                ->call('setPhase', $phase->id)
                ->call('createTask');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('title', 'Carry ring to Mordor')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_task_with_full_details()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->create();
            $project = $phase->project;
            $client = $project->client;
            $due_date = $this->faker->dateTimeThisMonth;

            $response = Livewire::test(Create::class)
                ->call('load')
                ->call('setClient', $client->id)
                ->call('setProject', $project->id)
                ->call('setPhase', $phase->id)
                ->set('title', 'Find The One')
                ->set('description', 'The Oracle predicts Morpheus will find The One')
                ->set('due_date', $due_date)
                ->set('task_type', 'detail')
                ->set('add_file_uploader', true)
                ->set('priority', 2)
                ->set('hidden', true)
                ->call('createTask');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('title', 'Find The One')
                ->where('description', 'The Oracle predicts Morpheus will find The One')
                ->where('due_date', Carbon::parse($due_date))
                ->where('type', 'detail')
                ->where('add_file_uploader', true)
                ->where('priority', 2)
                ->where('visible', false)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_task_that_belongs_to_team()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->create();
            $project = $phase->project;
            $client = $project->client;
            $team = Team::factory()->create();

            $response = Livewire::test(Create::class)
                ->call('load')
                ->call('setClient', $client->id)
                ->call('setProject', $project->id)
                ->call('setPhase', $phase->id)
                ->set('title', 'Destroy The Flood')
                ->call('assignTeam', $team->id)
                ->call('createTask');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('title', 'Destroy The Flood')
                ->whereHas('teams')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function task_can_have_urls()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->create();
            $project = $phase->project;
            $client = $project->client;
            $team = Team::factory()->create();

            $response = Livewire::test(Create::class)
                ->call('load')
                ->call('setClient', $client->id)
                ->call('setProject', $project->id)
                ->call('setPhase', $phase->id)
                ->set('title', 'Build a van')
                ->call('addNewURL')
                ->set('urls', ['www.something.com'])
                ->set('url_labels', ['something'])
                ->call('createTask');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('title', 'Build a van')
                ->whereHas('urls')
                ->exists());
            $this->assertTrue(Url::where('label', 'something')->where('url', 'www.something.com')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_action_is_create_when_an_admin_creates_a_task()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->create();
            $project = $phase->project;
            $client = $project->client;

            $response = Livewire::test(Create::class)
                ->call('load')
                ->call('setClient', $client->id)
                ->call('setProject', $project->id)
                ->call('setPhase', $phase->id)
                ->set('title', 'Build a van')
                ->call('createTask');
            $response->assertHasNoErrors();

            $this->assertTrue(Action::where('type', 'model_created')->where('actionable_id', $phase->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_task_visibility_from_task_row()
        {
            $this->actingAs(User::factory()->admin()->create());

            $task = Task::factory()->visible()->create();

            $response = Livewire::test(TaskRow::class, ['task' => $task])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('id', $task->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_task_visibility_from_task_show()
        {
            $this->actingAs(User::factory()->admin()->create());

            $task = Task::factory()->visible()->create();

            $response = Livewire::test(Show::class, ['task' => $task])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('id', $task->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_task_visibility_from_task_card()
        {
            $this->actingAs(User::factory()->admin()->create());

            $task = Task::factory()->visible()->create();

            $response = Livewire::test(TaskCard::class, ['task' => $task])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('id', $task->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function task_can_change_its_status()
        {
            $this->actingAs(User::factory()->admin()->create());

            $task = Task::factory()->create();

            $response = Livewire::test(StatusChanger::class, ['model' => $task])
                ->call('updateStatus', 'completed');
            $response->assertHasNoErrors();
            $this->assertTrue(Task::where('id', $task->id)->where('status', 'completed')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_task_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();

            $this->get(route('admin.tasks.show', $task->id))->assertSeeLivewire('admin.tasks.edit');

            $response = Livewire::test(Edit::class, ['task' => $task])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_task()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();

            $response = Livewire::test(Edit::class, ['task' => $task])
                ->call('load')
                ->set('title', 'Destroy Sith')
                ->call('saveTask');
            $this->assertTrue(Task::where('title', 'Destroy Sith')->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_task()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Row
            $task1 = Task::factory()->create();
            $response = Livewire::test(TaskRow::class, ['task' => $task1])
                ->call('destroy');
            $response->assertStatus(200);
            $this->assertFalse(Task::where('id', $task1->id)->exists());

            // Show
            $task2 = Task::factory()->create();
            $phase_id = $task2->phase_id;
            $response = Livewire::test(Show::class, ['task' => $task2])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.phases.show', $phase_id));
            $this->assertFalse(Task::where('id', $task2->id)->exists());

            // Card
            $task3 = Task::factory()->create();
            $client_id = $task3->client_id;
            $response = Livewire::test(TaskCard::class, ['task' => $task3])
                ->call('destroy');
            $response->assertStatus(200);
            $this->assertFalse(Task::where('id', $task3->id)->exists());
        }
    }
