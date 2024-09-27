<?php

    namespace App\Http\Livewire\Admin\Tasks;

    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Task;
    use App\Traits\Collapsible;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $client;

        public $setClient = true;

        public $project;

        public $setProject = true;

        public $projects;

        public $setPhase = true;

        public $phase;

        public $team;

        public $status = 'all';

        public $filter_visible = null;

        public $perPage = 10;

        public $listeners = ['taskUpdated' => '$refresh', 'taskCreated' => '$refresh', 'taskDeleted' => '$refresh'];

        public function mount() {

        }

        /**
         * Change filter
         *
         * @param $status
         */
        public function updateStatus($status) {
            $this->status = $status;
            $this->mount();
        }

        /**
         * Change visible filter
         *
         * @param $status
         */
        public function setVisibleFilter($status) {
            if($this->filter_visible == $status){
                $this->filter_visible = null;
            }else{
                $this->filter_visible = $status;
            }
            $this->mount();
        }

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 10;
        }

        public function render() {
            // Where is this component?
            $tasks = Task::query();

            if ( $this->client ) { // For single Client page
                $tasks->where('client_id', $this->client->id);
            }

            if ( $this->project ) { // For single Project page
                $tasks->where('project_id', $this->project->id);
            }

            if ( $this->phase ) { // For single Phase page
                $tasks->where('phase_id', $this->phase->id);
            }

            if ( $this->team ) { // For single Team page
                $tasks->whereHas('teams', function ($t) {
                    $t->where('team_id', $this->team->id);
                });
            }

            // Filter -----
            if ( $this->status != 'all' ) {
                $tasks->where('status', $this->status);
            }

            if ( $this->filter_visible == 'visible' ) {
                $tasks->where('visible', true);
            } elseif ( $this->filter_visible == 'hidden' ) {
                $tasks->where('visible', false);
            }

            $tasks = $tasks
                ->select('id', 'client_id', 'title', 'due_date', 'type', 'add_file_uploader', 'priority', 'status', 'visible', 'completed_at', 'created_at')
                ->orderBy('priority', 'desc')
                ->with([
                    'teams'
                ])
                ->paginate($this->perPage);

            return view('livewire.admin.tasks.index-card', ['tasks' => $tasks]);
        }
    }
