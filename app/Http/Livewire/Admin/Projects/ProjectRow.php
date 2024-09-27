<?php

namespace App\Http\Livewire\Admin\Projects;

use App\Models\Project;
use App\Traits\HasMenu;
use Livewire\Component;

class ProjectRow extends Component
{
    use HasMenu;

    public $project;

    protected $listeners = ['projectUpdated' => '$refresh'];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function toggleVisibility() {
        $this->project->update([
            'visible' => ! $this->project->visible
        ]);
        $this->emit('projectUpdated');
        $this->mount($this->project);
    }

    /**
     * Delete the project
     */
    public function destroy() {
        $this->project->delete();
        $this->emit('projectDeleted');
        flash('Project Deleted')->success()->livewire($this);
    }

    public function render()
    {
        return view('livewire.admin.projects.project-row');
    }
}
