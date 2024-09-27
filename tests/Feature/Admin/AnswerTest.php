<?php

    namespace Tests\Feature\Admin;

    use App\Http\Livewire\Admin\Answers\AnswerCard;
    use App\Http\Livewire\Admin\Answers\Create;
    use App\Http\Livewire\Admin\Answers\Edit;
    use App\Http\Livewire\Admin\Questions\QuestionCard;
    use App\Models\Answer;
    use App\Models\Client;
    use App\Models\Question;
    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Http\UploadedFile;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Livewire;
    use Tests\TestCase;

    class AnswerTest extends TestCase
    {
        use RefreshDatabase;
        use WithFaker;

        /** Useful Commands ==================== **/
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();


        /** @test */
        public function an_admin_can_see_the_answer_form_for_a_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $this->get(route('admin.questions.index'))->assertSeeLivewire('admin.questions.create');

            $question = Question::factory()->create();
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['question' => $question, 'client_id' => $client->id])
                ->call('openSlideout');

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_create_an_answer_for_a_detail_question()
        {
            $user = User::factory()->admin()->create();
            $this->actingAs($user);

            $question = Question::factory()->create();
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['question' => $question, 'client_id' => $client->id])
                ->call('load')
                ->set('answer', '42')
                ->call('createAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('answer', '42')
                ->where('question_id', $question->id)
                ->where('user_id', $user->id)
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_upload_a_file_to_answer_a_question()
        {
            $user = User::factory()->admin()->create();
            $this->actingAs($user);

            $question = Question::factory()->create(['add_file_uploader' => true]);
            $client = Client::factory()->create();
            Storage::fake('files');

            $file = UploadedFile::fake()->image('some-photo.jpg');

            $response = Livewire::test(Create::class, ['question' => $question, 'client_id' => $client->id])
                ->call('load')
                ->set('files', array($file))
                ->call('createAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('question_id', $question->id)
                ->where('user_id', $user->id)
                ->whereHas('files')
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_create_an_answer_for_a_multiple_choice_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $question = Question::factory()->create(['type' => 'multi_choice']);
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['question' => $question, 'client_id' => $client->id])
                ->call('load')
                ->set('choices', ['To Survive', 'To Create'])
                ->call('createAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('question_id', $question->id)
                ->whereJsonContains('choices', ['To Survive', 'To Create'])->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_create_an_answer_for_a_select_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $question = Question::factory()->create(['type' => 'select']);
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['question' => $question, 'client_id' => $client->id])
                ->call('load')
                ->set('choices', ['To Survive'])
                ->call('createAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('question_id', $question->id)
                ->whereJsonContains('choices', ['To Survive'])->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_create_an_answer_for_a_boolean_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $question = Question::factory()->create(['type' => 'boolean']);
            $client = Client::factory()->create();

            $response = Livewire::test(Create::class, ['question' => $question, 'client_id' => $client->id])
                ->call('load')
                ->set('choices', ['Yes'])
                ->call('createAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('question_id', $question->id)
                ->whereJsonContains('choices', ['Yes'])->exists());

            $response->assertStatus(200);
        }

        /** @test */
        public function an_admin_can_see_the_edit_answer_form_for_a_question()
        {
            $this->actingAs(User::factory()->admin()->create());

            $answer = Answer::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $answer->question, 'question_answer' => $answer, 'client_id' => $answer->client_id])
                ->call('openSlideout');

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_an_answer()
        {
            $this->actingAs(User::factory()->admin()->create());

            $answer = Answer::factory()->create();

            $response = Livewire::test(Edit::class, ['question' => $answer->question, 'question_answer' => $answer, 'client_id' => $answer->client_id])
                ->call('load')
                ->set('answer', '42')
                ->call('saveAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('id', $answer->id)
                ->where('answer', '42')
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_edit_an_answer_with_a_file()
        {
            $user = User::factory()->admin()->create();
            $this->actingAs($user);

            $answer = Answer::factory()->create();
            $answer->question->update(['add_file_uploader' => true]);

            Storage::fake('files');

            $file = UploadedFile::fake()->image('some-photo.jpg');

            $response = Livewire::test(Edit::class, ['question' => $answer->question, 'question_answer' => $answer, 'client_id' => $answer->client_id])
                ->call('load')
                ->set('files', array($file))
                ->call('saveAnswer');

            $response->assertHasNoErrors();
            $this->assertTrue(Answer::where('id', $answer->id)
                //->where('user_id', $user->id)
                ->whereHas('files')
                ->exists());

            $response->assertStatus(200);
        }
        /** @test */
        public function an_admin_can_delete_an_answer()
        {
            $this->actingAs(User::factory()->admin()->create());
            // Card
            $answer1 = Answer::factory()->create();
            $response = Livewire::test(AnswerCard::class, ['answer' => $answer1, 'question' => $answer1->question])
                ->call('deleteAnswer');
            $response->assertStatus(200);
            $this->assertFalse(Answer::where('id', $answer1->id)->exists());

            // Question Card
            $answer2 = Answer::factory()->create();
            $client_id = $answer2->client_id;
            $response = Livewire::test(QuestionCard::class, ['question' => $answer2->question, 'client_id' => $answer2->client_id])
                ->call('deleteAnswer');
            $response->assertStatus(200);
            $this->assertFalse(Answer::where('id', $answer2->id)->exists());
        }
    }
