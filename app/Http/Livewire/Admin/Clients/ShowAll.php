<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Client;
    use Livewire\Component;
    use Livewire\WithPagination;

    class ShowAll extends Component
    {
        use WithPagination;

        public $perPage = 20;

        public $simple = false;

        public $sortField = 'title';

        public $sortAsc = true;

        public $search = '';

        public $listeners = ['clientCreated' => '$refresh', 'clientUpdated' => '$refresh', 'clientDeleted' => '$refresh'];

        public function sortBy($field) {
            if ( $this->sortField === $field ) {
                $this->sortAsc = ! $this->sortAsc;
            } else {
                $this->sortAsc = true;
            }

            $this->sortField = $field;
        }

        public function mount() {

        }

        public function render() {
            $clients = Client::query();
            if($this->search !== ''){
                $clients = $clients->where('title', 'like', '%' . $this->search . '%');
            }
            $clients = $clients->autoloadIndex()
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perPage);

            return view('livewire.admin.clients.show-all', [
                'clients' => $clients,
            ]);
        }
    }
