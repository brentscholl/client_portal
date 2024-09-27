<?php

namespace App\Http\Livewire\Admin\Tutorials;

use App\Models\Tutorial;
use App\Traits\HasMenu;
use Livewire\Component;

class TutorialRow extends Component
{
    use HasMenu;

    public $tutorial;

    public function mount(Tutorial $tutorial)
    {
        $this->tutorial = $tutorial;
    }

    public function destroy() {
        $this->tutorial->delete();
        $this->emit('tutorialDeleted');
        flash('Tutorial Deleted')->success()->livewire($this);
    }

    public function render()
    {
        return view('livewire.admin.tutorials.tutorial-row');
    }
}
