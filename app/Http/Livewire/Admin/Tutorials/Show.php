<?php

    namespace App\Http\Livewire\Admin\Tutorials;

    use App\Models\Tutorial;
    use Illuminate\Database\Eloquent\Collection;
    use Livewire\Component;

    class Show extends Component
    {
        public $tutorial;

        public $clients;

        public $embed_id;

        protected $listeners = ['tutorialUpdated' => '$refresh'];

        public function mount(Tutorial $tutorial) {
            $this->tutorial = $tutorial;

            // Get a list of clients that are being shown this tutorial
            $clients_collection = new Collection();
            if ( $this->tutorial->clients->count() > 0 ) {
                // Get all clients attached to tutorial
                $clients_collection = $clients_collection->merge($this->tutorial->clients);
            } elseif ( $this->tutorial->services->count() > 0 ) {
                //  Get all services attached to tutorial, then get all clients attached to each service
                foreach ( $this->tutorial->services as $service ) {
                    $clients_collection = $clients_collection->merge($service->clients);
                }
            } elseif ( $this->tutorial->packages->count() > 0 ) {
                // Get all packages attached to tutorial, then get all projects attached to packages, then get all clients attached to each project
                foreach ( $this->tutorial->packages as $package ) {
                    if ( $package->projects->count() > 0 ) {
                        foreach ( $package->projects as $project ) {
                            $clients_collection = $clients_collection->push($project->client);
                        }
                    }
                }
            }
            // Get rid of duplicate clients.
            $this->clients = $clients_collection->unique('id')->sortBy('title', 0);

            // Get proper id for tutorial video embed
            $this->embed_id = str_replace('/share/', '', parse_url($this->tutorial->video_url)['path']);
        }

        public function destroy() {
            $this->tutorial->delete();
            flash('Tutorial Deleted')->success();
            return redirect()->route('admin.tutorials.index');
        }

        public function render() {
            return view('livewire.admin.tutorials.show');
        }
    }
