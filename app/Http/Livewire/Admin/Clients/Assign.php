<?php

namespace App\Http\Livewire\Admin\Clients;

use App\Models\Action;
use App\Models\Client;
use App\Models\Service;
use Livewire\Component;

class Assign extends Component
{
    public $model;

    public $assignable_clients = [];

    public $client_ids = [];

    public $modalOpen = false;

    public $clients;

    public function load() {
        $this->assignable_clients = Client::all();
        $this->client_ids = $this->model->clients()->pluck('client_id')->toArray();
        $this->modalOpen = true;
    }

    /**
     * Assign client to service
     * @param $client_id
     */
    public function assign($client_id) {
        $this->model->clients()->attach($client_id);
        $client = Client::find($client_id);

        $action = Action::create([
            'client_id' => $client->id,
            'user_id' => auth()->user()->id,
            'type' => 'service_assigned',
            'value' => $this->model->title,
            'relation_id' => $this->model->id,
            'actionable_type' => get_class($client),
            'actionable_id' => $client->id,
        ]);

        $this->emit('actionCreated');

        $this->emit('clientUpdated');
        $this->load();
    }

    /**
     * Unassign client from service
     * @param $client_id
     */
    public function unassign($client_id) {
        $this->model->clients()->detach($client_id);
        $this->emit('clientUpdated');
        $this->mount();
        $this->load();
    }

    public function render()
    {
        return view('livewire.admin.clients.assign');
    }
}
