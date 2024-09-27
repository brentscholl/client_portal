<?php

    namespace App\Http\Livewire\Admin\Projects;

    use App\Models\Assignee;
    use App\Models\Project;
    use App\Traits\Collapsible;
    use Illuminate\Database\Eloquent\Collection;
    use Livewire\Component;
    use Livewire\WithPagination;

    class IndexCard extends Component
    {
        use Collapsible, WithPagination;

        public $client;

        public $setClient = true;

        public $service;

        public $setService = true;

        public $showService = true;

        public $package;

        public $tasks;

        public $user;

        public $status = 'all';

        public $filter_visible = null;

        public $perPage = 5;

        public $listeners = ['projectUpdated' => '$refresh', 'projectCreated' => '$refresh', 'projectDeleted' => '$refresh'];

        public function mount() {
        }

        /**
         * Update the status filter on the index card
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
            $this->perPage = $this->perPage + 5;
        }

        public function render() {
            // Where is this component?
            $projects = Project::query();

            if($this->user){
                $assignments = Assignee::with([
                    'assigneeable',
                    'assigneeable.project:id,title'
                ])->where('assigneeable_type', 'App\Models\Phase')->where('user_id', $this->user->id)->get();
                if($assignments){
                    $projects_id = [];
                    foreach($assignments as $assignment){
                        array_push($projects_id, $assignment->assigneeable->project->id);
                    }
                    $projects = $projects->whereIn('id', $projects_id);
                }
            }

            if ( $this->client ) {
                $projects->where('client_id', $this->client->id);
            }

            if ( $this->service ) {
                $projects->where('service_id', $this->service->id);
            }

            if ( $this->package ) {
                $projects->whereHas('packages', function ($p) {
                    $p->where('package_id', $this->package->id);
                });
            }

            if ( $this->status != 'all' ) {
                $projects->where('status', $this->status);
            }

            if ( $this->filter_visible == 'visible' ) {
                $projects->where('visible', true);
            } elseif ( $this->filter_visible == 'hidden' ) {
                $projects->where('visible', false);
            }

            $projects = $projects->with([
                'client:id,title',
                'packages',
                'service:id,slug,title',
                'phases:id,client_id,project_id,step,status',
            ])->orderBy('due_date', 'desc')->paginate($this->perPage);
            return view('livewire.admin.projects.index-card', ['projects' =>  $projects]);
        }
    }
