<?php

    namespace App\Http\Livewire\Admin\Questions;

    use App\Models\File;
    use App\Models\Question;
    use App\Models\Answer;
    use App\Traits\HasMenu;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Component;

    class QuestionCard extends Component
    {
        use HasMenu;

        public $question;

        public $question_answered = false;

        public $setService = true;

        public $setClient = true;

        public $setProject = true;

        public $setPackage = true;

        public $answer;

        public $client_id;

        protected $listeners = ['questionUpdated' => '$refresh'];

        public function mount(Question $question) {
            $this->question = $question;
        }

        /**
         * Delete the answer.
         */
        public function deleteAnswer() {
            if ( $this->answer ) {
                $this->answer->delete();
                $this->emit('answerDeleted');
                flash('Answer Deleted')->success()->livewire($this);
                $this->render();
                $this->mount($this->question);
            } else {
                flash('Question does not have an answer to delete')->error()->livewire($this);
            }
        }

        /**
         * Delete file.
         */
        public function deleteFile($id) {
            $file = File::find($id);
            if ( $file ) {
                $file->delete();
                $this->emit('fileDeleted');
                flash('File Deleted')->success()->livewire($this);
            } else {
                flash('File does not exist')->error()->livewire($this);
            }
        }

        /**
         * Delete Question
         * todo: handle file delete
         */
        public function destroy() {
            // Delete question
            $this->question->delete();
            $this->emit('questionDeleted');
            flash('Question Deleted')->success()->livewire($this);
        }

        public function render() {
            if ( $this->client_id ) {
                // If on client page, get the client's answer to the question
                $this->answer = $this->question->answers->first();
                $this->question_answered = $this->answer ? true : false;
            }
            return view('livewire.admin.questions.question-card');
        }
    }
