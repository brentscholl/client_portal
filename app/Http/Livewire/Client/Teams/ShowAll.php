<?php

    namespace App\Http\Livewire\Client\Teams;

    use App\Models\User;
    use Livewire\Component;

    class ShowAll extends Component
    {
        public $perPage = 21;

        public $listeners = ['userUpdated' => '$refresh', 'userCreated' => '$refresh', 'userDeleted' => '$refresh'];

        /**
         * Increase Pagination
         */
        public function loadMore()
        {
            $this->perPage = $this->perPage + 6;
        }

        public function render()
        {
            $users = User::where('client_id', auth()->user()->client_id)->paginate($this->perPage);

            return view('livewire.client.teams.show-all', ['users' => $users]);
        }
    }
