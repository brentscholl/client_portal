<?php

    namespace App\Http\Livewire\Client\Questions;

    use App\Models\Answer;
    use App\Models\File;
    use App\Models\Question;
    use App\Traits\HasFileUploader;
    use App\Traits\HasMenu;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class QuestionCard extends Component
    {
        use HasFileUploader;

        public $question;

        public $question_answered = false;

        public $queried_answer;

        public $answer;

        public $choices = [];

        public $setClient = true;

        public $client;

        public $is_editing = false;

        public $show_file_uploader = false;

        protected $listeners = [
            'questionUpdated' => '$refresh',
            'answerDeleted'   => '$refresh',
            'answerCreated'   => '$refresh',
            'answerUpdated'   => '$refresh',
            'fileDeleted'     => '$refresh',
        ];

        public function mount(Question $question)
        {
            $this->question = $question;
            $this->client = auth()->user()->client;
        }

        public function rules()
        {
            if ($this->question->type == 'detail') {
                if ($this->question->add_file_uploader) {
                    return [
                        'answer' => 'sometimes|required_without:files|string|nullable',
                        'files'  => 'sometimes|required_without:answer|max:50000',
                    ];
                } else {
                    return [
                        'answer' => 'sometimes|required|string|nullable',
                    ];
                }
            } else {
                return [
                    'files'   => 'sometimes|max:50000',
                    'choices' => 'sometimes|required|max:50000',
                ];
            }
        }

        protected $messages = [
            'files.max'        => 'Cannot upload more than 50MB.',
            'choices.required' => 'Please choose an answer.',
        ];

        /**
         * Delete the answer.
         */
        public function deleteAnswer()
        {
            abort_unless($this->queried_answer->client_id == auth()->user()->client_id, 403);

            if ($this->queried_answer) {
                $this->queried_answer->delete();
                $this->emit('answerDeleted');
                flash('Answer Deleted')->success()->livewire($this);
                $this->render();
                $this->mount($this->question);
            } else {
                flash('Question does not have an answer to delete')->error()->livewire($this);
            }
        }

        public function editAnswer()
        {
            $this->is_editing = true;
            $this->answer = $this->queried_answer->answer;
            $this->choices = json_decode($this->queried_answer->choices);
            $this->availableChoices = $this->question->choices;
        }

        /**
         * Delete file.
         */
        public function deleteFile($id)
        {
            $file = File::find($id);
            if ($file) {
                $file->delete();
                if ($this->queried_answer->answer == null && $this->queried_answer->choices == null) {
                    $this->queried_answer->delete();
                    $this->emit('answerDeleted');
                }
                $this->emit('fileDeleted');
                flash('File Deleted')->success()->livewire($this);
            } else {
                flash('File does not exist')->error()->livewire($this);
            }
        }

        /**
         * Create the new answer
         */
        public function createAnswer()
        {
            $this->validate();

            DB::beginTransaction();
            $choices = null;
            if ($this->question->type == 'multi_choice') {
                $choices = json_encode($this->choices);
            } elseif ($this->question->type == 'select' || $this->question->type == 'boolean') {
                if (is_array($this->choices)) {
                    $choices = json_encode($this->choices);
                } else {
                    $choices = json_encode([$this->choices]);
                }
            }
            $attr = [
                'client_id'   => auth()->user()->client_id,
                'user_id'     => auth()->user()->id,
                'question_id' => $this->question->id,
                'answer'      => $this->answer ? $this->answer : null,
                'choices'     => $choices,
            ];

            if ($this->is_editing && $this->queried_answer) {
                $this->queried_answer->update($attr);
                $this->emit('answerUpdated');
                // Store files
            } else {
                // Create question
                $this->queried_answer = Answer::create($attr);
                $this->emit('answerCreated');
                // Store files
            }

            $this->addNewFile($this->queried_answer);

            DB::commit();

            $this->reset([
                'answer',
                'choices',
                'is_editing',
            ]);

            $this->mount($this->question->fresh());

            flash('Question Answered!')->success()->livewire($this);
        }

        public function render()
        {
            $this->queried_answer = $this->question->answers->where('client_id', auth()->user()->client_id)->first();
            $this->question_answered = $this->queried_answer ? true : false;

            return view('livewire.client.questions.question-card');
        }
    }
