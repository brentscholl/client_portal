<?php

namespace App\Http\Livewire\Client\Tasks;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAll extends Component
{
    use WithPagination;

    public $status = 'all';

    public $perPage = 10;

    public function mount()
    {

    }

    /**
     * Update the status filter on the index card
     * @param $status
     */
    public function updateStatus($status) {
        $this->status = $status;
        $this->render();
    }

    /**
     * Increase Pagination
     */
    public function loadMore() {
        $this->perPage = $this->perPage + 5;
    }

    public function render()
    {
        $tasks = Task::query();

        $tasks = $tasks->where('client_id', auth()->user()->client_id)->where('visible', true);

        if ( $this->status != 'all' ) {
            $tasks = $tasks->where('status', $this->status);
        }

        $tasks = $tasks->select('id', 'client_id', 'title', 'due_date', 'type', 'add_file_uploader', 'priority', 'status', 'completed_at', 'created_at')
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'desc')->paginate($this->perPage);

        return view('livewire.client.tasks.show-all', ['tasks' =>  $tasks]);
    }
}
