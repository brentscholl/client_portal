<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Client;
    use App\Traits\HasMenu;
    use Livewire\Component;

    class ClientRow extends Component
    {
        use HasMenu;

        public $client;

        public $simple = false;

        public $listeners = ['clientDeleted' => '$refresh'];

        public function mount(Client $client)
        {
            $this->client = $client;
        }

        public function destroy()
        {
            $this->client->delete();
            flash('Client Deleted')->success();

            return redirect()->route('admin.clients.index');
        }

        public function render()
        {
            return view('livewire.admin.clients.client-row');
        }
    }
