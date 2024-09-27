<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\AssignUser;
    use App\Http\Livewire\Admin\Phases\Create;
    use App\Http\Livewire\Admin\Phases\Edit;
    use App\Http\Livewire\Admin\Phases\PhaseCard;
    use App\Http\Livewire\Admin\Phases\Show;
    use App\Http\Livewire\Admin\StatusChanger;
    use App\Models\Action;
    use App\Models\Assignee;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Url;
    use App\Models\User;
    use Carbon\Carbon;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class PhaseTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_see_phase()
        {
            $this->actingAs(User::factory()->admin()->create());
            $phase = Phase::factory()->create();
            $response = Livewire::test(Show::class, ['phase' => $phase]);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_create_phase_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();

            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_phase_with_minimum_details()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();

            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('load')
                ->set('title', 'Copy')
                ->call('createPhase');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('title', 'Copy')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_phase_with_full_details()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();
            $due_date = $this->faker->dateTimeThisMonth;

            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('load')
                ->set('title', 'Copy')
                ->set('description', 'Some description for some phase.')
                ->set('due_date', $due_date)
                ->call('createPhase');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('title', 'Copy')
                ->where('due_date', Carbon::parse($due_date))
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function phases_have_correct_step_when_created()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();

            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('load')
                ->set('title', 'Copy')
                ->call('createPhase');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('title', 'Copy')
                ->where('step', 1)
                ->exists());

            // Make another
            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('load')
                ->set('title', 'Copy')
                ->call('createPhase');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('title', 'Copy')
                ->where('step', 2)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function phases_can_have_urls()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();

            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('load')
                ->call('load')
                ->set('title', 'Copy')
                ->call('addNewURL')
                ->set('urls', ['www.something.com'])
                ->set('url_labels', ['something'])
                ->call('createPhase');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('title', 'Copy')->whereHas('urls')->exists());
            $this->assertTrue(Url::where('label', 'something')->where('url', 'www.something.com')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_action_is_created_when_an_admin_creates_a_phase()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();

            $response = Livewire::test(Create::class, ['project' => $project])
                ->call('load')
                ->set('title', 'Copy')
                ->call('createPhase');
            $response->assertHasNoErrors();
            $this->assertTrue(Action::where('type', 'model_created')->where('actionable_id', $project->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_phase_visibility_from_phase_card()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->visible()->create();

            $response = Livewire::test(PhaseCard::class, ['phase' => $phase])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('id', $phase->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_change_phase_visibility_from_phase_show()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->visible()->create();

            $response = Livewire::test(Show::class, ['phase' => $phase])
                ->call('toggleVisibility');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('id', $phase->id)->where('visible', 0)
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function prhase_can_change_its_status()
        {
            $this->actingAs(User::factory()->admin()->create());

            $phase = Phase::factory()->create();

            $response = Livewire::test(StatusChanger::class, ['model' => $phase])
                ->call('updateStatus', 'completed');
            $response->assertHasNoErrors();
            $this->assertTrue(Phase::where('id', $phase->id)->where('status', 'completed')
                ->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_phase_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $phase = Phase::factory()->create();

            $this->get(route('admin.phases.show', $phase->id))->assertSeeLivewire('admin.phases.edit');

            $response = Livewire::test(Edit::class, ['phase' => $phase])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_phase()
        {
            $this->actingAs(User::factory()->admin()->create());
            $phase = Phase::factory()->create();

            $this->get(route('admin.phases.show', $phase->id))->assertSeeLivewire('admin.phases.edit');

            $response = Livewire::test(Edit::class, ['phase' => $phase])
                ->call('load')
                ->set('title', 'Copy Updated')
                ->call('savePhase');
            $this->assertTrue(Phase::where('title', 'Copy Updated')->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_phase()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Row
            $phase1 = Phase::factory()->create();
            $response = Livewire::test(PhaseCard::class, ['phase' => $phase1])
                ->call('destroy');
            $response->assertStatus(200);
            $this->assertFalse(Phase::where('id', $phase1->id)->exists());

            // Show
            $phase2 = Phase::factory()->create();
            $project_id = $phase2->project_id;
            $response = Livewire::test(Show::class, ['phase' => $phase2])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.projects.show', $project_id));
            $this->assertFalse(Phase::where('id', $phase2->id)->exists());
        }

        /** @test */
        public function other_phases_update_step_order_when_phase_is_deleted()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Row
            $project = Project::factory()->create();
            $phase1 = Phase::factory()->create(['project_id' => $project->id, 'step' => 1]);
            $phase2 = Phase::factory()->create(['project_id' => $project->id, 'step' => 2]);
            $phase3 = Phase::factory()->create(['project_id' => $project->id, 'step' => 3]);

            $phase2->delete();

            $this->assertTrue(Phase::where('id', $phase1->id)->where('step', 1)->exists());
            $this->assertTrue(Phase::where('id', $phase3->id)->where('step', 2)->exists());
        }

        /** @test */
        public function a_team_member_can_be_assigned_to_phase()
        {
            $this->actingAs(User::factory()->admin()->create());
            $phase = Phase::factory()->create();
            $user = User::create()->admin()->create();
            $response = Livewire::test(AssignUser::class, ['model' => $phase])
                ->call('load')
                ->call('assign', $user->id);
            $response->assertHasNoErrors();

            $this->assertTrue(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($phase))
                ->where('assigneeable_id', $phase->id)
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'user_assigned')->where('actionable_id', $phase->id)->exists());
        }

        /** @test */
        public function a_team_member_can_be_unassigned_from_phase()
        {
            $this->actingAs(User::factory()->admin()->create());
            $phase = Phase::factory()->create();
            $user = User::create()->admin()->create();
            $response = Livewire::test(AssignUser::class, ['model' => $phase])
                ->call('load')
                ->call('assign', $user->id);
            $response->assertHasNoErrors();

            $this->assertTrue(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($phase))
                ->where('assigneeable_id', $phase->id)
                ->exists());

            // Action should be made aswell
            $this->assertTrue(Action::where('type', 'user_assigned')->where('actionable_id', $phase->id)->exists());

            // Unnassign
            $response = Livewire::test(AssignUser::class, ['model' => $phase])
                ->call('load')
                ->call('unassign', $user->id);
            $response->assertHasNoErrors();

            $this->assertFalse(Assignee::where('user_id', $user->id)
                ->where('assigneeable_type', get_class($phase))
                ->where('assigneeable_id', $phase->id)
                ->exists());

            $this->assertFalse(Action::where('type', 'user_assigned')->where('actionable_id', $phase->id)->exists());
        }
    }
