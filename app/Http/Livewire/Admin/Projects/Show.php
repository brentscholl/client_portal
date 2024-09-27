<?php

    namespace App\Http\Livewire\Admin\Projects;

    use App\Models\Project;
    use App\Traits\HasTabs;
    use Livewire\Component;

    class Show extends Component
    {
        use HasTabs;

        public $project;

        protected $queryString = ['tab' => ['except' => 'details']];
        
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
        public function destroy() {
            $project_client_id = $this->project->client_id;
            $this->project->delete();
            flash('Project Deleted')->success();
            return redirect()->route('admin.clients.show', $project_client_id);
        }

        public function render() {
            return view('livewire.admin.projects.show');
        }
    }
