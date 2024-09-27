<?php

    namespace App\Http\Livewire\Admin\Packages;

    use App\Traits\HasTabs;
    use Illuminate\Database\Eloquent\Collection;
    use Livewire\Component;

    class Show extends Component
    {
        use HasTabs;

        public $package;

        public $clients;

        protected $listeners = ['packageUpdated' => '$refresh'];

        protected $queryString = ['tab' => ['except' => 'details']];

        public function mount() {
            // Get the clients that have this package
            $clients_collection = new Collection();
            foreach ( $this->package->projects as $project ) {
                $clients_collection = $clients_collection->push($project->client);
            }
            $this->clients = $clients_collection->unique('id')->sortBy('title', 0);
        }

        public function destroy() {
            $package_service_id = $this->package->service_id;
            $this->package->delete();
            flash('Package Deleted')->success();

            return redirect()->route('admin.services.show', $package_service_id);
        }

        public function render() {
            return view('livewire.admin.packages.show');
        }
    }
