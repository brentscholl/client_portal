<?php

    namespace App\Http\Livewire\Admin\Projects;

    use App\Models\Client;
    use App\Models\Project;
    use App\Models\Service;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $perPage = 20;

        public $sortField = 'created_at';

        public $sortAsc = false;

        public $search = '';

        public $clients;

        public $selectedClient;

        public $services;

        public $selectedService;

        public $listeners = ['projectCreated' => '$refresh', 'projectUpdated' => '$refresh', 'projectDeleted' => '$refresh'];


        public function sortBy($field) {
            if ( $this->sortField === $field ) {
                $this->sortAsc = ! $this->sortAsc;
            } else {
                $this->sortAsc = true;
            }

            $this->sortField = $field;
        }

        public function mount() {
            $this->clients = Client::all('title', 'id');
            $this->services = Service::all('title', 'id');
        }

        public function toggleVisibility($id) {
            $project = Project::find($id);
            $project->update([
                'visible' => ! $project->visible
            ]);
            $this->emit('projectUpdated');
        }

        public function destroy($id) {
            $project = Project::find($id);
            $project->delete();
            flash('Project Deleted')->success();
            return redirect()->route('admin.projects.index');
        }

        public function render() {
            $projects = Project::query();

            if($this->search !== ''){
                $projects = $projects->where('title', 'like', '%' . $this->search . '%');
            }

            if ( $this->selectedClient ) {
                $projects = $projects->where('client_id', $this->selectedClient);
            }

            if ( $this->selectedService ) {
                $projects = $projects->where('service_id', $this->selectedService);
            }

            $projects = $projects->autoloadIndex()
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

            return view('livewire.admin.projects.show-all', [
                'projects' => $projects->paginate($this->perPage),
            ]);
        }
    }
