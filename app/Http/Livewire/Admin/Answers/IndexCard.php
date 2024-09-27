<?php

    namespace App\Http\Livewire\Admin\Answers;

    use App\Models\Answer;
    use App\Traits\Collapsible;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $question;

        public $perPage = 10;

        public $listeners = ['answerUpdated' => '$refresh', 'answerCreated' => '$refresh', 'answerDeleted' => '$refresh'];

        public function mount() {
        }

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 10;
        }

        public function render() {
            $answers = Answer::query();
            $answers = $answers->where('question_id', $this->question->id);
            $answers = $answers->with([
                'client:id,title',
                'files',
                'user:id,first_name,last_name'
            ])->paginate($this->perPage);
            return view('livewire.admin.answers.index-card', ['answers' => $answers]);
        }
    }
