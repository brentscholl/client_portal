<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Client;
    use App\Models\User;
    use App\Traits\Collapsible;
    use Livewire\Component;

    class ClientReps extends Component
    {
        use Collapsible;

        public $client;

        public $perPage = 9;

        public $listeners = ['userUpdated' => '$refresh', 'userCreated' => '$refresh', 'userDeleted' => '$refresh'];

        public function mount(Client $client) {
            $this->client = $client;
        }

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 3;
        }

        public function render() {
            $reps = User::where('client_id', $this->client->id)->paginate($this->perPage);

            return view('livewire.admin.clients.client-reps', ['reps' => $reps]);
        }
    }
