<?php

    namespace App\Http\Livewire\Admin\Tutorials;

    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Service;
    use App\Models\Tutorial;
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

        public $selectedClient;

        public $services;

        public $selectedService;

        public $packages;

        public $selectedPackage;

        public $listeners = ['tutorialUpdated' => '$refresh', 'tutorialCreated' => '$refresh', 'tutorialDeleted' => '$refresh'];

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
            $this->services = Service::all('title', 'id');
            $this->packages = Package::all('title', 'id');
        }

        public function render() {
            $tutorials = Tutorial::query();

            if ( $this->search !== '' ) {
                $tutorials = $tutorials->where('title', 'like', '%' . $this->search . '%');
            }

            if ( $this->selectedClient ) {
                $tutorials->whereHas('clients', function($q){
                    $q->where('tutorialable_id', $this->selectedClient)->where('tutorialable_type', 'App\Models\Client');
                });
            }

            if ( $this->selectedService ) {
                $tutorials->whereHas('services', function($q){
                    $q->where('tutorialable_id', $this->selectedService)->where('tutorialable_type', 'App\Models\Service');
                });
            }

            if ( $this->selectedPackage ) {
                $tutorials->whereHas('packages', function($q){
                    $q->where('tutorialable_id', $this->selectedPackage)->where('tutorialable_type', 'App\Models\Package');
                });
            }

            $tutorials->autoloadIndex()->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

            return view('livewire.admin.tutorials.show-all', [
                'tutorials' => $tutorials->paginate($this->perPage),
            ]);
        }
    }
