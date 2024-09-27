<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Client;
    use App\Models\Task;
    use App\Models\Team;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $perPage = 20;

        public $sortField = 'created_at';

        public $sortAsc = true;

        public $search = '';

        public $clients;

        public $teams;

        public $selectedClient;

        public $selectedTeam;

        public $listeners = ['taskCreated' => '$refresh', 'taskUpdated' => '$refresh', 'taskDeleted' => '$refresh'];

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
            $this->teams = Team::all('title', 'id');
        }

        public function render() {
            $tasks = Task::query();

            if($this->search !== ''){
                $tasks = $tasks->where('title', 'like', '%' . $this->search . '%');
            }

            if ( $this->selectedClient ) {
                $tasks = $tasks->where('client_id', $this->selectedClient);
            }

            if ( $this->selectedTeam ) {
                $tasks = $tasks->whereHas('teams', function($q){
                    $q->where('team_id', $this->selectedTeam)->where('teamable_type', 'App\Models\Task');
                });
            }

            $tasks = $tasks->with([
                'project:id,title',
                'phase:id,project_id,title,step',
                'client:id,title',
                'teams:id,title',
            ])->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

            return view('livewire.admin.tasks.show-all', [
                'tasks' => $tasks->paginate($this->perPage),
            ]);
        }
    }
