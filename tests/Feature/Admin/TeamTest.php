<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Teams\AddMember;
    use App\Http\Livewire\Admin\Teams\Create;
    use App\Http\Livewire\Admin\Teams\Edit;
    use App\Http\Livewire\Admin\Teams\Show;
    use App\Http\Livewire\Admin\Teams\TeamMemberCard;
    use App\Models\Team;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class TeamTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();
        /*
         * todo: add edit team test
         */

        /** @test */
        public function an_admin_can_open_the_create_team_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_create_a_team_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('title', 'Gryffindor')
                ->set('description', $this->faker->sentence())
                ->call('addTeam');
            $response->assertHasNoErrors();
            $this->assertTrue(Team::where('title', 'Gryffindor')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_team_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $team = Team::factory()->create();

            //$this->get(route('admin.users.index'))->assertSeeLivewire('admin.users.edit');
            $this->get(route('admin.teams.show', $team->id))->assertSeeLivewire('admin.teams.edit');

            $response = Livewire::test(Edit::class, ['team' => $team])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());
            $team = Team::factory()->create();

            $response = Livewire::test(Edit::class, ['team' => $team])
                ->call('load')
                ->set('title', 'Hufflepuff')
                ->call('saveTeam');
            $response->assertHasNoErrors();
            $this->assertTrue(Team::where('title', 'Hufflepuff')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());

            // Show
            $team = Team::factory()->create();
            $response = Livewire::test(Show::class, ['team' => $team])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.teams.index'));
            $this->assertFalse(Team::where('id', $team->id)->exists());
        }

        /** @test */
        public function an_admin_can_add_a_user_to_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());
            $team = Team::factory()->create();
            $user = User::factory()->admin()->create();

            $response = Livewire::test(AddMember::class, ['team' => $team])
                ->call('load')
                ->call('assign', $user->id);

            $response->assertHasNoErrors();
            $this->assertTrue(Team::where('id', $team->id)
                ->whereHas('users')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_remove_a_user_to_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());
            $team = Team::factory()->create();
            $user = User::factory()->admin()->create();
            $team->users()->attach($user->id);

            $response = Livewire::test(TeamMemberCard::class, ['team' => $team, 'user' => $user])
                ->call('unassign', $user->id);

            $response->assertHasNoErrors();
            $this->assertTrue(Team::where('id', $team->id)
                ->whereDoesntHave('users')->exists());

            $response->assertStatus(200);
        }
    }
