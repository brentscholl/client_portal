<?php

    namespace App\Http\Livewire\Admin\Projects;

    use App\Models\Project;
    use App\Traits\HasMenu;
    use Illuminate\Support\Carbon;
    use Livewire\Component;

    class ProjectCard extends Component
    {
        use HasMenu;

        public $project;

        public $showService = true;

        protected $listeners = ['projectUpdated' => '$refresh'];

        public function mount(Project $project) {
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

        public function render() {
            return view('livewire.admin.projects.project-card');
        }
    }
