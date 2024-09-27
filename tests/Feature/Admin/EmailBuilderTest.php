<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Emails\Create;
    use App\Http\Livewire\Admin\Emails\EmailTemplateCard;
    use App\Http\Livewire\Admin\Emails\HistoryItem;
    use App\Http\Livewire\Admin\Emails\SentHistory;
    use App\Models\Client;
    use App\Models\Email;
    use App\Models\EmailTemplate;
    use App\Models\Project;
    use App\Models\Question;
    use App\Models\Task;
    use App\Models\Tutorial;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Livewire\Livewire;
    use Tests\TestCase;

    class EmailBuilderTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        /** @test */
        public function an_admin_can_open_the_create_email_form()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('openSlideout');

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_open_the_create_email_form_for_editing_existing_email_template()
        {
            $this->actingAs(User::factory()->admin()->create());
            $email_template = EmailTemplate::factory()->create();

            $response = Livewire::test(Create::class, ['email_template' => $email_template, 'is_editing' => true, 'client' => $email_template->client])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_add_reps_to_email_recipients()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->call('openRecipientModal')
                ->call('assignRecipient', $rep)
                ->assertSet('recipient_ids', [$rep->id]);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_remove_reps_to_email_recipients()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->call('openRecipientModal')
                ->call('assignRecipient', $rep)
                ->assertSet('recipient_ids', [$rep->id])
                ->call('unassignRecipient', $rep->id)
                ->assertSet('recipient_ids', []);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_add_all_reps_to_email_recipients()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep1 = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep1->id]);
            $rep2 = User::factory()->create(['client_id' => $client->id]);
            $rep3 = User::factory()->create(['client_id' => $client->id]);
            $rep4 = User::factory()->create(['client_id' => $client->id]);

            $response = Livewire::test(Create::class, ['client' => $client->fresh()])
                ->call('load')
                ->call('openRecipientModal')
                ->call('assignAllRecipient')
                ->assertCount('recipient_ids', 4);

            $response->assertStatus(200);
        }

        /** @test */
        public function the_email_has_the_primary_contact_as_default_recipient()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->assertNotSet('recipients', null);

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_save_an_email_template_as_draft()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('saveDraft');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_update_an_email_template()
        {
            $this->actingAs(User::factory()->admin()->create());
            $email_template = EmailTemplate::factory()->create();

            $response = Livewire::test(Create::class, ['email_template' => $email_template, 'is_editing' => true, 'client' => $email_template->client])
                ->call('load')
                ->set('subject', 'Updated Welcome')
                ->call('saveDraft');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Updated Welcome')->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_a_simple_email_template()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_message_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'message', 0)
                ->set('layout.0.inputs.message', 'Test message')
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%Test message%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_an_alert_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'alert', 0)
                ->set('layout.0.inputs.alert', 'Test message')
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%Test message%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_link_to_dashboard_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'link_to_dashboard', 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%link_to_dashboard%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_tasks_list_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();
            $client = $task->client;
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'tasks', 0)
                ->call('setProject', $task->project->id, 0)
                ->call('setPhase', $task->phase->id, 0)
                ->call('setTask', $task->id, $task->title, 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%'.$task->title.'%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_projects_list_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();
            $client = $project->client;
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'projects', 0)
                ->call('setProject', $project->id, 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%'.$project->title.'%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_questions_list_component()
        {
            $this->actingAs(User::factory()->admin()->create());

            $question = Question::factory()->create(['is_onboarding' => true]);
            $project = Project::factory()->create();
            $client = $project->client;
            $project->questions()->attach($question);
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'questions', 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%questions%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_tutorials_list_component()
        {
            $this->actingAs(User::factory()->admin()->create());

            $tutorial = Tutorial::factory()->create();
            $project = Project::factory()->create();
            $client = $project->client;
            $project->service->tutorials()->attach($tutorial);
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'tutorials', 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%tutorials%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_single_task_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $task = Task::factory()->create();
            $client = $task->client;
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'single_task', 0)
                ->call('setProject', $task->project_id, 0)
                ->call('setPhase', $task->phase_id, 0)
                ->call('setTask', $task->id, $task->title, 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%'.$task->title.'%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_single_project_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $project = Project::factory()->create();
            $client = $project->client;
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'single_project', 0)
                ->call('setProject', $project->id, 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%'.$project->title.'%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_single_question_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $question = Question::factory()->create(['is_onboarding' => true]);
            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'single_question', 0)
                ->call('setQuestion', $question->id, $question->body, 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%'.$question->body.'%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_email_template_with_a_single_tutorial_component()
        {
            $this->actingAs(User::factory()->admin()->create());
            $tutorial = Question::factory()->create();
            $project = Project::factory()->create();
            $project->service->tutorials()->attach($tutorial);
            $client = $project->client;
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'single_tutorial', 0)
                ->call('setProject', $project->id, 0)
                ->call('setTutorial', $tutorial->id, $tutorial->title, 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%'.$tutorial->title.'%')->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_remove_a_component()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'message', 0)
                ->call('addComponent', 'alert', 1)
                ->call('addComponent', 'message', 2)
                ->call('removeComponent', 1)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }

        /** @test */
        public function an_email_template_can_be_scheduled_to_send_later()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->set('schedule_email_to_send', true)
                ->set('email_schedule.set_send_date', 1)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('schedule_email_to_send', true)
                ->where('set_send_date', true)
                ->exists());
            $this->assertFalse(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }
        /** @test */
        public function an_email_template_can_be_scheduled_to_send_now()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->set('schedule_email_to_send', true)
                ->set('email_schedule.set_send_date', 0)
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('schedule_email_to_send', true)
                ->where('set_send_date', false)
                ->exists());
            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            $response->assertStatus(200);
        }
        /** @test */
        public function an_email_template_can_be_deleted()
        {
            $this->actingAs(User::factory()->admin()->create());

            $email_template = EmailTemplate::factory()->create();

            $response = Livewire::test(EmailTemplateCard::class, ['email_template' => $email_template])
                ->call('destroy');
            $response->assertHasNoErrors();
            $this->assertFalse(EmailTemplate::where('id', $email_template->id)
                ->exists());
            $response->assertStatus(200);
        }
        /** @test */
        public function an_email_templates_schedule_can_be_stopped()
        {
            $this->actingAs(User::factory()->admin()->create());

            $email_template = EmailTemplate::factory()->create([
                'schedule_email_to_send' => true,
                'repeat' => 'daily',
                'ends' => 'never',
            ]);

            $response = Livewire::test(EmailTemplateCard::class, ['email_template' => $email_template])
                ->call('stopSchedule');
            $response->assertHasNoErrors();
            $this->assertTrue(EmailTemplate::where('id', $email_template->id)
                ->where('schedule_email_to_send', false)
                ->exists());
            $response->assertStatus(200);
        }
        /** @test */
        public function an_email_template_can_be_sent_on_command()
        {
            $this->actingAs(User::factory()->admin()->create());

            $client = Client::factory()->create();
            $rep = User::factory()->create(['client_id' => $client->id]);
            $client->update(['primary_contact' => $rep->id]);
            $client = $client->fresh();

            $response = Livewire::test(Create::class, ['client' => $client])
                ->call('load')
                ->set('subject', 'Welcome')
                ->call('addComponent', 'message', 0)
                ->set('layout.0.inputs.message', 'Test message')
                ->call('sendEmail');
            $response->assertHasNoErrors();

            $this->assertTrue(EmailTemplate::where('subject', 'Welcome')
                ->where('layout', 'like', '%Test message%')->exists());

            $this->assertTrue(Email::where('client_id', $client->id)->exists());
            Email::where('client_id', $client->id)->firstOrFail()->delete();
            $this->assertFalse(Email::where('client_id', $client->id)->exists());

            $email_template = EmailTemplate::where('subject', 'Welcome')->firstOrFail();

            $response = Livewire::test(EmailTemplateCard::class, ['email_template' => $email_template])
                ->call('sendEmail');
            $response->assertHasNoErrors();
            $this->assertTrue(Email::where('client_id', $client->id)->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function the_sent_history_component_works()
        {
            $this->actingAs(User::factory()->admin()->create());
            $client = Client::factory()->create();

            $response = Livewire::test(SentHistory::class, ['client' => $client])
                ->call('openSlideout');

            $response->assertStatus(200);
        }
        /** @test */
        public function the_sent_history_item_component_works()
        {
            $this->actingAs(User::factory()->admin()->create());
            $email = Email::factory()->create();

            $response = Livewire::test(HistoryItem::class, ['email' => $email])
                ->call('openSlideout');

            $response->assertStatus(200);
        }
    }
