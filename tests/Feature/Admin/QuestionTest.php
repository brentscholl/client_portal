<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Questions\Create;
    use App\Http\Livewire\Admin\Questions\Edit;
    use App\Http\Livewire\Admin\Questions\QuestionRow;
    use App\Http\Livewire\Admin\Questions\Show;
    use App\Http\Livewire\Admin\Questions\ShowAll;
    use App\Models\Answer;
    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Project;
    use App\Models\Question;
    use App\Models\Service;
    use App\Models\Team;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class QuestionTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_see_question_index_page()
        {
            $this->actingAs(User::factory()->admin()->create());
            $response = $this->get(route('admin.questions.index'));
            $response->assertStatus(200);

            $response = Livewire::test(ShowAll::class);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_open_the_create_question_form()
        {
            $this->actingAs(User::factory()->admin()->create());

            $this->get(route('admin.questions.index'))->assertSeeLivewire('admin.questions.create');

            $response = Livewire::test(Create::class)
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_question_for_client()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('tagline', 'Causality')
                ->set('assign_type', 'client')
                ->call('setClient', $client->id)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereHas('clients')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_question_for_project()
        {
            $this->actingAs(User::factory()->admin()->create());

            $project = Project::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'project')
                ->call('setClient', $project->client_id)
                ->call('setProject', $project->id)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereHas('projects')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_question_for_services()
        {
            $this->actingAs(User::factory()->admin()->create());

            $service = Service::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'service')
                ->call('assignService', $service->id)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereHas('services')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_question_for_package()
        {
            $this->actingAs(User::factory()->admin()->create());

            $package = Package::factory()->create();
            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'package')
                ->call('setService', $package->service_id)
                ->call('setPackage', $package->id)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereHas('packages')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_onboarding_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'onboarding')
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->where('is_onboarding', true)->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_assign_a_question_to_a_team()
        {
            $this->actingAs(User::factory()->admin()->create());

            $team = Team::factory()->create();

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'onboarding')
                ->set('assign_to_team', true)
                ->call('assignTeam', $team->id)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereHas('teams')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_multiple_choice_question()
        {
            $this->actingAs(User::factory()->admin()->create());
            $choices = [0 => 'To Survive', 1 => 'To Create', 2 => 'To Learn'];

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'onboarding')
                ->set('question_type', 'multi_choice')
                ->call('addNewChoice')
                ->set('choices', $choices)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereJsonContains('choices', ['To Survive', 'To Create', 'To Learn'])->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_select_question()
        {
            $this->actingAs(User::factory()->admin()->create());
            $choices = [0 => 'To Survive', 1 => 'To Create', 2 => 'To Learn'];

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'onboarding')
                ->set('question_type', 'select')
                ->call('addNewChoice')
                ->set('choices', $choices)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')
                ->whereJsonContains('choices', ['To Survive', 'To Create', 'To Learn'])->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_boolean_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'onboarding')
                ->set('question_type', 'boolean')
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_question_with_file_uploader()
        {
            $this->actingAs(User::factory()->admin()->create());

            $response = Livewire::test(Create::class)
                ->call('load')
                ->set('body', 'What is the meaning of life?')
                ->set('assign_type', 'onboarding')
                ->set('add_file_uploader', true)
                ->call('createQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the meaning of life?')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_a_question_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create();

            $this->get(route('admin.questions.show', $question->id))->assertSeeLivewire('admin.questions.edit');

            $response = Livewire::test(Edit::class, ['question' => $question])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_question()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $question])
                ->call('load')
                ->set('assign_type', 'onboarding')
                ->set('body', 'What is the purpose of life?')
                ->call('saveQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the purpose of life?')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_question_to_be_for_client()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create();
            $client = Client::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $question])
                ->call('load')
                ->set('assign_type', 'client')
                ->set('body', 'What is the purpose of life?')
                ->call('setClient', $client->id)
                ->call('saveQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the purpose of life?')
                ->whereHas('clients')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_question_to_be_for_project()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create();
            $project = Project::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $question])
                ->call('load')
                ->set('assign_type', 'project')
                ->set('body', 'What is the purpose of life?')
                ->call('setClient', $project->client_id)
                ->call('setProject', $project->id)
                ->call('saveQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the purpose of life?')
                ->whereHas('projects')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_question_to_be_for_service()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create();
            $service = Service::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $question])
                ->call('load')
                ->set('assign_type', 'service')
                ->set('body', 'What is the purpose of life?')
                ->call('assignService', $service->id)
                ->call('saveQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the purpose of life?')
                ->whereHas('services')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_edit_a_question_to_be_for_package()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create();
            $package = Package::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $question])
                ->call('load')
                ->set('assign_type', 'package')
                ->set('body', 'What is the purpose of life?')
                ->call('setService', $package->service_id)
                ->call('setPackage', $package->id)
                ->call('saveQuestion');
            $response->assertHasNoErrors();
            $this->assertTrue(Question::where('body', 'What is the purpose of life?')
                ->whereHas('packages')->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_delete_a_question()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Row
            $question1 = Question::factory()->create();
            $response = Livewire::test(QuestionRow::class, ['question' => $question1])
                ->call('destroy');
            $response->assertStatus(200);
            $this->assertFalse(Question::where('id', $question1->id)->exists());

            // Show
            $question2 = Question::factory()->create();
            $client_id = $question2->client_id;
            $response = Livewire::test(Show::class, ['question' => $question2])
                ->call('destroy');
            $response->assertStatus(200);
            $response->assertRedirect(route('admin.questions.index'));
            $this->assertFalse(Question::where('id', $question2->id)->exists());
        }
    }
