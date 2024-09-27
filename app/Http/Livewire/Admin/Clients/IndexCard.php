<?php

    namespace App\Http\Livewire\Admin\Clients;

    use App\Models\Client;
    use App\Traits\Collapsible;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $clients;

        public $model;

        public $listeners = ['clientUpdated' => '$refresh', 'clientCreated' => '$refresh', 'clientDeleted' => '$refresh'];

        public function render() {
            return view('livewire.admin.clients.index-card');
        }
    }
