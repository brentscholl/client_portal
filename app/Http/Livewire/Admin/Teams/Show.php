<?php

    namespace App\Http\Livewire\Admin\Teams;

    use App\Models\Team;
    use App\Traits\HasTabs;
    use Illuminate\Database\Eloquent\Collection;
    use Livewire\Component;

    class Show extends Component
    {
        use HasTabs;

        public $team;

        public $clients;

        protected $queryString = ['tab' => ['except' => 'details']];

        protected $listeners = ['teamUpdated' => '$refresh', 'teamMemberRemoved' => '$refresh', 'teamMemberAdded' => '$refresh'];

        public function mount(Team $team) {
            $this->team = $team;

            // Get a list of clients that are being shown this team
            $clients_collection = new Collection();
            if ( $this->team->questions->count() > 0 ) {
                //  Get all questions attached to team, then get all clients attached to each question
                foreach ( $this->team->questions as $question ) {
                    $clients_collection = $clients_collection->merge($question->clients);
                }
            } elseif ( $this->team->tasks->count() > 0 ) {
                //  Get all tasks attached to team, then get all clients attached to each task
                foreach ( $this->team->tasks as $task ) {
                    $clients_collection = $clients_collection->add($task->client);
                }
            }
            // Get rid of duplicate clients.
            $this->clients = $clients_collection->unique('id')->sortBy('title', 0);
        }

        public function destroy() {
            $this->team->delete();
            flash('Team Deleted')->success();

            return redirect()->route('admin.teams.index');
        }

        public function render() {
            return view('livewire.admin.teams.show');
        }
    }
