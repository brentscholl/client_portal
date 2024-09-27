<?php

    namespace App\Http\Livewire\Admin;

    use App\Models\Action;
    use App\Models\Client;
    use App\Models\Service;
    use Livewire\Component;

    class AssignService extends Component
    {
        public $client;

        public $assignable_services = [];

        public $service_ids = [];

        public $modalOpen = false;

        public $services;

        public $project_service_ids = [];

        protected $listeners = ['clientUpdated' => '$refresh', 'servicesUpdated' => '$refresh'];

        public function mount(Client $client) {
            $this->client = $client;
            foreach ( $this->client->projects as $project ) {
                array_push($this->project_service_ids, $project->service_id);
            }
        }

        public function load() {
            $this->assignable_services = Service::all();
            $this->service_ids = $this->client->services()->pluck('service_id')->toArray();
            $this->modalOpen = true;
        }

        public function rules() {
            return [
                'client'        => ['required'],
            ];
        }

        /**
         * Assign service to client
         * @param $service_id
         */
        public function assign($service_id) {
            $this->validate();
            $this->client->services()->attach($service_id);
            $this->emit('clientUpdated');

            $service = Service::find($service_id);

            $action = Action::create([
                'client_id' => $this->client->id,
                'user_id' => auth()->user()->id,
                'type' => 'service_assigned',
                'value' => $service->title,
                'relation_id' => $service_id,
                'actionable_type' => get_class($this->client),
                'actionable_id' => $this->client->id,
            ]);

            $this->emit('actionCreated');

            $this->emit('servicesUpdated');
            $this->mount($this->client);
            $this->load();
        }

        /**
         * Unassign service from client
         * @param $service_id
         */
        public function unassign($service_id) {
            $this->client->services()->detach($service_id);
            $this->emit('clientUpdated');
            $this->emit('servicesUpdated');
            $this->mount($this->client);
            $this->load();
        }

        public function render() {
            return view('livewire.admin.assign-service');
        }
    }
