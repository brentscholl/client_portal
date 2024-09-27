<?php

namespace App\Http\Livewire\Client\Projects;

use App\Models\Assignee;
use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class ShowAll extends Component
{
    use WithPagination;

    public $status = 'all';

    public $perPage = 5;

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
        $projects = Project::query();

        $projects = $projects->where('client_id', auth()->user()->client_id)->where('visible', true);

        if ( $this->status != 'all' ) {
            $projects = $projects->where('status', $this->status);
        }

        $projects = $projects->with([
            'client:id,title',
            'packages',
            'service:id,slug,title',
            'phases:id,client_id,project_id,step,status,title',
        ])->orderBy('due_date', 'desc')->paginate($this->perPage);

        return view('livewire.client.projects.show-all', ['projects' =>  $projects]);
    }
}
