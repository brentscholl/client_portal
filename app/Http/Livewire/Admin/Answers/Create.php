<?php

    namespace App\Http\Livewire\Admin\Answers;

    use App\Models\Client;
    use App\Models\File;
    use App\Models\Question;
    use App\Models\Answer;
    use App\Traits\Answers\Answerable;
    use App\Traits\HasFileUploader;
    use App\Traits\Slideable;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Answerable, Slideable, HasFileUploader;

        public function mount(Question $question)
        {
            $this->question = $question;
        }

        /**
         *todo: add file uploader
         */
        public function load()
        {
            $this->availableChoices = $this->question->choices;
        }

        public function rules()
        {
            if($this->question->type == 'detail'){
                if($this->question->add_file_uploader) {
                    return [
                        'answer' => 'sometimes|required_without:files|string|nullable',
                        'files'  => 'sometimes|required_without:answer|max:50000',
                    ];
                }else{
                    return [
                        'answer' => 'sometimes|required|string|nullable',
                    ];
                }
            }else{
                return [
                    'files'   => 'sometimes|max:50000',
                    'choices' => 'sometimes|required|max:50000',
                ];
            }
        }

        protected $messages = [
            'files.max' => 'Cannot upload more than 50MB.',
        ];

        /**
         * Create the new answer
         */
        public function createAnswer()
        {
            $this->validate();

            DB::beginTransaction();

            // Create question
            $answer = Answer::create([
                'client_id'   => $this->setClient ? $this->client->id : $this->client_id,
                'user_id'     => auth()->user()->id,
                'question_id' => $this->question->id,
                'answer'      => $this->answer ? $this->answer : null,
                'choices'     => $this->question->type == 'multi_choice' ? json_encode($this->choices) : ($this->question->type == 'select' || $this->question->type == 'boolean' ? json_encode([$this->choices]) : null),
            ]);

            // Store files
            $this->addNewFile($answer);

            DB::commit();

            $this->reset([
                'answer',
                'choices',
            ]);

            $this->closeSlideout();

            $this->emit('answerCreated');
            $this->emit('questionUpdated');

            flash('Question Answered!')->success()->livewire($this);
        }

        public function render()
        {
            return view('livewire.admin.answers.create');
        }
    }
