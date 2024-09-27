<?php

namespace App\Http\Livewire\Client\Tasks;

use Livewire\Component;

class TaskCard extends Component
{
    public $task;

    protected $listeners = ['taskUpdated' => '$refresh'];


    public function render()
    {
        return view('livewire.client.tasks.task-card');
    }
}
