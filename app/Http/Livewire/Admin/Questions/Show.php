<?php

    namespace App\Http\Livewire\Admin\Questions;

    use App\Models\Client;
    use App\Models\Question;
    use Illuminate\Database\Eloquent\Collection;
    use Livewire\Component;

    class Show extends Component
    {
        public $question;

        public $clients;

        protected $listeners = ['questionUpdated' => '$refresh', 'assigneesUpdated' => '$refresh'];

        public function mount(Question $question) {
            $this->question = $question;

            // Get a list of clients that are being asked this question
            $clients_collection = new Collection();
            if ( $this->question->clients->count() > 0 ) {
                // Get all clients attached to question
                $clients_collection = $clients_collection->merge($this->question->clients);
            } elseif ( $this->question->projects->count() > 0 ) {
                // Get all projects attached to question, then get the clients with those projects
                foreach ( $this->question->projects as $project ) {
                    $clients_collection = $clients_collection->push($project->client);
                }
            } elseif ( $this->question->services->count() > 0 ) {
                //  Get all services attached to question, then get all clients attached to each service
                foreach ( $this->question->services as $service ) {
                    $clients_collection = $clients_collection->merge($service->clients);
                }
            } elseif ( $this->question->packages->count() > 0 ) {
                // Get all packages attached to question, then get all projects attached to packages, then get all clients attached to each project
                foreach ( $this->question->packages as $package ) {
                    if ( $package->projects->count() > 0 ) {
                        foreach ( $package->projects as $project ) {
                            $clients_collection = $clients_collection->push($project->client);
                        }
                    }
                }
            }
            // Get rid of duplicate clients.
            $this->clients = $clients_collection->unique('id')->sortBy('title', 0);
        }

        public function destroy() {
            $this->question->delete();
            flash('Question Deleted')->success();
            return redirect()->route('admin.questions.index');
        }

        public function render() {
            return view('livewire.admin.questions.show');
        }
    }
