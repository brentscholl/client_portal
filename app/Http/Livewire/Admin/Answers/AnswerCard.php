<?php

    namespace App\Http\Livewire\Admin\Answers;

    use App\Models\File;
    use App\Traits\HasMenu;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Component;

    class AnswerCard extends Component
    {
        use HasMenu;

        public $answer;

        public $question;

        protected $listeners = ['questionUpdated' => '$refresh', 'answerCreated' => '$refresh', 'answerUpdated' => '$refresh', 'answerDeleted' => '$refresh'];

        /**
         * Delete an answer
         * todo: handle file delete
         */
        public function deleteAnswer() {
            $this->answer->delete();
            $this->emit('answerDeleted');
            flash('Answer Deleted')->success();
        }

        /**
         * Delete file.
         */
        public function deleteFile($id) {
            $file = File::find($id);
            if ( $file ) {
                $file->resources()->detach();
                $file->questions()->detach();
                $file->answers()->detach();
                $file->tasks()->detach();
                Storage::disk('files')->delete($file->filename);
                $file->delete();
                $this->emit('fileDeleted');
                flash('File Deleted')->success()->livewire($this);
                $this->mount();
            } else {
                flash('File does not exist')->error()->livewire($this);
            }
        }

        public function render() {
            return view('livewire.admin.answers.answer-card');
        }
    }
