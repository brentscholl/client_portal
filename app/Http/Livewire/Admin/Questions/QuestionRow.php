<?php

namespace App\Http\Livewire\Admin\Questions;

use App\Models\Question;
use App\Traits\HasMenu;
use Livewire\Component;

class QuestionRow extends Component
{
    use HasMenu;

    public $question;

    public function mount(Question $question)
    {
        $this->question = $question;
    }

    public function destroy() {
        $this->question->delete();
        $this->emit('questionDeleted');
        flash('Question Deleted')->success();
    }

    public function render()
    {
        return view('livewire.admin.questions.question-row');
    }
}
