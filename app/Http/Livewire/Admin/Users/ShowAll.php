<?php

    namespace App\Http\Livewire\Admin\Users;

    use App\Models\Client;
    use App\Models\Team;
    use App\Models\User;
    use App\Traits\Impersonateable;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $perPage = 20;

        public $sortField = 'first_name';

        public $sortAsc = true;

        public $search = '';

        public $clients;

        public $selectedClient;

        public $types;

        public $selectedType;

        public $teams;

        public $selectedTeam;

        public $listeners = ['userCreated' => '$refresh', 'userUpdated' => '$refresh', 'userDeleted' => '$refresh'];


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
            $users = User::query();

            if ($this->search !== ''){
                $users = $users->where('first_name', 'like', '%'. $this->search . '%')
                ->orwhere('last_name', 'like', '%'. $this->search . '%')
                ->orwhere('email', 'like', '%'. $this->search . '%')
                ->orwhere('phone', 'like', '%'. $this->search . '%');
            }

            if ( $this->selectedClient ) {
                $users->where('client_id', $this->selectedClient);
            }

            if ( $this->selectedTeam ) {
                $users = $users->whereHas('teams', function ($q){
                    $q->where('teamable_type', 'App\Models\User')->where('team_id', $this->selectedTeam);
                });
            }

            if($this->selectedType){
                switch($this->selectedType){
                    case('admin'):
                        $users = $users->where('role', 'admin')->where('client_id', null);
                        break;
                    case('client'):
                        $users = $users->where('client_id', '!=', null);
                        break;
                }

            }

            $users= $users->with([
                'client:id,title',
                'teams:id,title'
            ])->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

            return view('livewire.admin.users.show-all', [
                'users' => $users->paginate($this->perPage),
            ]);
        }
    }
