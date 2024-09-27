<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Assignee;
    use App\Models\Client;
    use App\Traits\HasTabs;
    use Livewire\Component;

    class Show extends Component
    {
        use HasTabs;

        public $client;

        public $marketingAdvisors;

        protected $queryString = ['tab' => ['except' => 'details']];

        protected $listeners = ['clientUpdated' => '$refresh', 'assigneesUpdated' => '$refresh'];

        public function mount(Client $client) {
            $this->client = $client;
        }

        public function destroy() {
            $this->client->delete();
            flash('Client Deleted')->success();
            return redirect()->route('admin.clients.index');
        }

        public function render() {
            return view('livewire.admin.clients.show');
        }
    }
