<?php

    namespace App\Http\Livewire\Admin\Answers;

    use App\Models\Answer;
    use App\Models\File;
    use App\Traits\Answers\Answerable;
    use App\Traits\HasFileUploader;
    use App\Traits\Slideable;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Storage;
    use Livewire\Component;

    class Edit extends Component
    {
        use Answerable, Slideable, HasFileUploader;

        /**
         *todo: add file uploader
         */
        public function load() {
            $this->answer = $this->question_answer->answer;
            $this->choices = json_decode($this->question_answer->choices);
            $this->availableChoices = $this->question->choices;

        }

        public function rules() {
            return [
                'answer' => ['nullable', 'string'],
            ];
        }

        /**
         * Save the edited answer
         */
        public function saveAnswer() {
            $this->validate();

            DB::beginTransaction();

            // Update the answer
            $this->question_answer->update([
                'answer'  => $this->answer ? $this->answer : null,
                'choices' => $this->question->type == 'multi_choice' ? json_encode($this->choices) : ($this->question->type == 'select' || $this->question->type == 'boolean' ? json_encode([$this->choices]) : null),
            ]);

            // Store files
            $this->addNewFile($this->question_answer);

            DB::commit();

            $this->emit('answerUpdated');
            $this->closeSlideout();

            flash('Question Answer Updated!')->success()->livewire($this);
        }



        public function render() {
            return view('livewire.admin.answers.edit');
        }
    }
