<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\AssignUser;
    use App\Http\Livewire\Admin\Projects\Edit;
    use App\Http\Livewire\Admin\Projects\Create;
    use App\Http\Livewire\Admin\Projects\ProjectRow;
    use App\Http\Livewire\Admin\Projects\Show;
    use App\Http\Livewire\Admin\Projects\ShowAll;
    use App\Http\Livewire\Admin\StatusChanger;
    use App\Models\Action;
    use App\Models\Assignee;
    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Service;
    use App\Models\Url;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class ProjectTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_see_project_index_page()
        {
            $this->actingAs(User::factory()->admin()->create());
            $response = $this->get(route('admin.projects.index'));
            $response->assertStatus(200);

            $response = Livewire::test(ShowAll::class);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_create_project_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $this->get(route('admin.projects.index'))->assertSeeLivewire('admin.projects.create');

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_project_with_minimum_details()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Website')
                ->call('setClient', $client->id)
                ->call('setService', $service->id)
                ->call('createProject');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('title', 'Website')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_project_with_full_details()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $due_date = $this->faker->dateTimeThisMonth;
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Website')
                ->set('description', 'Some description for some website.')
                ->set('hidden', true)
                ->set('due_date', $due_date)
                ->set('client', $client)
                ->set('service', $service)
                ->call('createProject');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('title', 'Website')
                ->where('visible', false)
                ->where('due_date', Carbon::parse($due_date))
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_project_with_packages()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $package = Package::factory()->create(['service_id' => $service->id]);
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Website')
                ->set('client', $client)
                ->set('service', $service)
                ->call('assignPackage', $package->id)
                ->call('createProject');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('title', 'Website')->whereHas('packages')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function the_first_phase_is_created_when_a_project_is_created()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Website')
                ->set('client', $client)
                ->set('service', $service)
                ->call('createProject');
            $response->assertHasNoErrors();
            $project = Project::where('title', 'Website')->firstOrFail();
            $this->assertTrue(Phase::where('project_id', $project->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function projects_can_have_urls()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Website')
                ->set('client', $client)
                ->set('service', $service)
                ->call('addNewURL')
                ->set('urls', ['www.something.com'])
                ->set('url_labels', ['something'])
                ->call('createProject');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('title', 'Website')->whereHas('urls')->exists());
            $this->assertTrue(Url::where('label', 'something')->where('url', 'www.something.com')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_action_is_created_when_an_admin_creates_a_project()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Website')
                ->set('client', $client)
                ->set('service', $service)
                ->call('createProject');
            $response->assertHasNoErrors();
            $this->assertTrue(Action::where('type', 'model_created')->where('actionable_id', $client->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_project_visibility_from_project_row()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->visible()->create();

            $response = Livewire::test(ProjectRow::class, ['project' => $project])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('id', $project->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_project_visibility_from_project_show()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->visible()->create();

            $response = Livewire::test(Show::class, ['project' => $project])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('id', $project->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function project_can_change_its_status()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();

            $response = Livewire::test(StatusChanger::class, ['model' => $project])
                ->call('updateStatus', 'completed');
            $response->assertHasNoErrors();
            $this->assertTrue(Project::where('id', $project->id)->where('status', 'completed')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_project_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();

            $this->get(route('admin.projects.show', $project->id))->assertSeeLivewire('admin.projects.edit');

            $response = Livewire::test(Edit::class, ['project' => $project])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_project()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();

            $response = Livewire::test(Edit::class, ['project' => $project])
                ->call('load')
                ->set('title', 'Website Updated')
                ->call('saveProject');
            $this->assertTrue(Project::where('title', 'Website Updated')->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_project()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Row
            $project1 = Project::factory()->create();
            $response = Livewire::test(ProjectRow::class, ['project' => $project1])
                ->call('destroy');
            $response->assertStatus(200);
            $this->assertFalse(Project::where('id', $project1->id)->exists());
            $this->assertFalse(Phase::where('project_id', $project1->id)->exists());

            // Show
            $project2 = Project::factory()->create();
            $client_id = $project2->client_id;
            $response = Livewire::test(Show::class, ['project' => $project2])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.clients.show', $client_id));
            $this->assertFalse(Project::where('id', $project2->id)->exists());
            $this->assertFalse(Phase::where('project_id', $project1->id)->exists());
        }

        /** @test */
        public function a_marketing_advisor_can_be_assigned_to_project()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();
            $user = User::create()->admin()->create();
            $response = Livewire::test(AssignUser::class, ['model' => $project, 'assign_marketing_advisor' => true])
                ->call('load')
                ->call('assign', $user->id);
            $response->assertHasNoErrors();

            $this->assertTrue(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($project))
                ->where('assigneeable_id', $project->id)
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'user_assigned')->where('actionable_id', $project->id)->exists());
        }

        /** @test */
        public function a_marketing_advisor_can_be_unassigned_from_project()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();
            $user = User::create()->admin()->create();
            $response = Livewire::test(AssignUser::class, ['model' => $project, 'assign_marketing_advisor' => true])
                ->call('load')
                ->call('assign', $user->id);
            $response->assertHasNoErrors();

            $this->assertTrue(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($project))
                ->where('assigneeable_id', $project->id)
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'user_assigned')->where('actionable_id', $project->id)->exists());

            // Unnassign
            $response = Livewire::test(AssignUser::class, ['model' => $project, 'assign_marketing_advisor' => true])
                ->call('load')
                ->call('unassign', $user->id);
            $response->assertHasNoErrors();

            $this->assertFalse(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($project))
                ->where('assigneeable_id', $project->id)
                ->exists());

            $this->assertFalse(Action::where('type', 'user_assigned')->where('actionable_id', $project->id)->exists());
        }
    }
