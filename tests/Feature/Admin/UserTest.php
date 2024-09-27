<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Users\Create;
    use App\Http\Livewire\Admin\Users\Edit;
    use App\Http\Livewire\Admin\Users\Show;
    use App\Http\Livewire\Admin\Users\UserRow;
    use App\Models\Client;
    use App\Models\Team;
    use App\Models\User;
    use Hash;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Livewire;
    use Tests\TestCase;

    class UserTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_see_user_index_page()
        {
            $this->actingAs(User::factory()->admin()->create());
            $response = $this->get(route('admin.users.index'));

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_create_user_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $this->get(route('admin.users.index'))->assertSeeLivewire('admin.users.create');

            $response = Livewire::test(Create::class, ['setClient' => true])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_basic_user()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create(['primary_contact' => 1]);

            $response = Livewire::test(Create::class)
                ->set('first_name', 'Brent')
                ->set('last_name', 'Scholl')
                ->set('email', 'brent@stealthmedia.com')
                ->set('email_confirmation', 'brent@stealthmedia.com')
                ->set('password', 'P4ssword!')
                ->set('phone', '13064451234')
                ->set('position', 'Web Developer')
                ->set('user_type', 'basic')
                ->set('client', $client)
                ->call('addUser');
            $response->assertHasNoErrors();
            $this->assertTrue(User::where('first_name', 'Brent')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_cannot_create_a_basic_user_without_a_client()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->set('first_name', 'Brent')
                ->set('last_name', 'Scholl')
                ->set('email', 'brent@stealthmedia.com')
                ->set('email_confirmation', 'brent@stealthmedia.com')
                ->set('password', 'P4ssword!')
                ->set('phone', '13064451234')
                ->set('position', 'Web Developer')
                ->set('user_type', 'basic')
                ->set('client', null)
                ->call('addUser');
            $response->assertHasErrors(['client']);

            $response->assertStatus(200);
        }

        /** @test */
        public function a_basic_user_will_be_set_as_primary_contact_if_its_first()
        {
            // User will be set as primary contact if it is
            // the first user to be added to the client
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create(['primary_contact' => null]);

            $response = Livewire::test(Create::class)
                ->set('first_name', 'Brent')
                ->set('last_name', 'Scholl')
                ->set('email', 'brent@stealthmedia.com')
                ->set('email_confirmation', 'brent@stealthmedia.com')
                ->set('password', 'P4ssword!')
                ->set('phone', '13064451234')
                ->set('position', 'Web Developer')
                ->set('user_type', 'basic')
                ->set('client', $client)
                ->call('addUser');
            $response->assertHasNoErrors();
            $newUser = User::where('first_name', 'Brent')->firstOrFail();
            $client = $client->fresh();
            $this->assertTrue($client->primary_contact == $newUser->id);
        }

        /** @test */
        public function an_admin_can_create_an_admin_user()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class, ['setClient' => true])
                ->set('first_name', 'Brent')
                ->set('last_name', 'Scholl')
                ->set('email', 'brent@stealthmedia.com')
                ->set('email_confirmation', 'brent@stealthmedia.com')
                ->set('password', 'P4ssword!')
                ->set('phone', '13064451234')
                ->set('position', 'Web Developer')
                ->set('user_type', 'admin')
                ->call('addUser');
            $response->assertHasNoErrors();
            $this->assertTrue(User::where('first_name', 'Brent')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_admin_user_with_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());
            $team = Team::factory()->create();

            $response = Livewire::test(Create::class, ['setClient' => true])
                ->set('first_name', 'Brent')
                ->set('last_name', 'Scholl')
                ->set('email', 'brent@stealthmedia.com')
                ->set('email_confirmation', 'brent@stealthmedia.com')
                ->set('password', 'P4ssword!')
                ->set('phone', '13064451234')
                ->set('position', 'Web Developer')
                ->set('user_type', 'admin')
                ->set('team', $team)
                ->call('addUser');
            $response->assertHasNoErrors();
            $this->assertTrue(User::where('first_name', 'Brent')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_user_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $user = User::factory()->create();

            //$this->get(route('admin.users.index'))->assertSeeLivewire('admin.users.edit');
            $this->get(route('admin.users.show', $user->id))->assertSeeLivewire('admin.users.edit');

            $response = Livewire::test(Edit::class, ['user' => $user])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_user()
        {
            $this->actingAs(User::factory()->admin()->create());
            $user = User::factory()->create();

            $response = Livewire::test(Edit::class, ['user' => $user])
                ->call('load')
                ->set('first_name', 'Brent')
                ->call('saveUser');
            $response->assertHasNoErrors();
            $this->assertTrue(User::where('first_name', 'Brent')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_user_password()
        {
            $this->actingAs(User::factory()->admin()->create());
            $user = User::factory()->create();

            $response = Livewire::test(Edit::class, ['user' => $user])
                ->call('load')
                ->set('password', '123abc!')
                ->call('saveUser');
            $response->assertHasNoErrors();

            $this->assertTrue(Auth::attempt(['email' => $user->email, 'password' => '123abc!']));

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_user_from_basic_to_admin()
        {
            $this->actingAs(User::factory()->admin()->create());
            $user = User::factory()->create();
            $team = Team::factory()->create();

            $response = Livewire::test(Edit::class, ['user' => $user])
                ->call('load')
                ->set('user_type', 'admin')
                ->set('team', $team)
                ->call('saveUser');
            $response->assertHasNoErrors();

            $this->assertTrue(User::where('email', $user->email)->where('client_id', null)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_user_from_admin_to_basic()
        {
            $this->actingAs(User::factory()->admin()->create());
            $user = User::factory()->admin()->create();
            $this->assertTrue(($user->client_id == null));
            $client = Client::factory()->create();

            $response = Livewire::test(Edit::class, ['user' => $user])
                ->call('load')
                ->set('user_type', 'basic')
                ->set('client', $client)
                ->call('saveUser');
            $response->assertHasNoErrors();

            $this->assertTrue(User::where('email', $user->email)->where('client_id', $client->id)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_user()
        {
            $this->actingAs(User::factory()->admin()->create());
            // UserRow
            $user1 = User::factory()->create();
            $response = Livewire::test(UserRow::class, ['user' => $user1])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.users.index'));
            $this->assertFalse(User::where('id', $user1->id)->exists());

            // Show
            $user2 = User::factory()->create();
            $response = Livewire::test(Show::class, ['user' => $user2])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.users.index'));
            $this->assertFalse(User::where('id', $user2->id)->exists());
        }
    }
