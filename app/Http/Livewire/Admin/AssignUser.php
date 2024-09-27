<?php

    namespace App\Http\Livewire\Admin;

    use App\Models\Action;
    use App\Models\Assignee;
    use App\Models\User;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class AssignUser extends Component
    {
        public $model;

        public $assign_marketing_advisor = false;

        public $assignable_users = [];

        public $assignee_ids = [];

        public $modalOpen = false;

        public $assignees;

        public $allow_assign = true;

        public function mount() {
            $this->getAssignees();
        }

        public function load() {
            if ( $this->assign_marketing_advisor ) {
                $this->assignable_users = User::admin()->whereHas('teams', function ($t){
                    $t->where('slug', 'marketing-advisor');
                })->orderBy('first_name', 'asc')->get();
            } else {
                $this->assignable_users = User::admin()->orderBy('first_name', 'asc')->get();
            }

            $this->assignee_ids = $this->assignees->pluck('user_id')->toArray();
            $this->modalOpen = true;
        }

        /**
         * Get all users that can be assigned (staff)
         * todo: get users based on team
         */
        public function getAssignees() {
            if ( $this->assign_marketing_advisor ) { // For Marketing Advisors

                // Marketing Advisors are assign to clients and/or projects
                switch ( get_class($this->model) ) {
                    case('App\Models\Task'):
                    case('App\Models\Phase'):
                        // Get the Marketing Advisor when on Phase or Task page
                        $this->assignees = Assignee::whereHas('user', function ($q) {
                            $q->whereHas('teams', function ($t){
                                $t->where('slug', 'marketing-advisor');
                            })->orderBy('first_name', 'asc');
                        })->where(function ($w) {
                            // Get Marketing Advisors attached to model's project
                            $w->where('assigneeable_type', 'App\Models\Project')
                                ->where('assigneeable_id', $this->model->project_id);
                        })->orWhere(function ($o) {
                            // Get Marketing Advisors attached to model's client
                            $o->where('assigneeable_type', 'App\Models\Client')
                                ->where('assigneeable_id', $this->model->client_id);
                        })->get()->unique('user_id');
                        break;
                    case('App\Models\Project'):
                        // Get Marketing Advisor when on Project page.
                        $this->assignees = Assignee::whereHas('user', function ($q) {
                            $q->whereHas('teams', function ($t){
                                $t->where('slug', 'marketing-advisor');
                            })->orderBy('first_name', 'asc');
                        })->where(function ($w) {
                            // Get Marketing Advisors attached to project
                            $w->where('assigneeable_type', 'App\Models\Project')
                                ->where('assigneeable_id', $this->model->id);
                        })->orWhere(function ($o) {
                            // Get Marketing Advisors attached to project's client
                            $o->where('assigneeable_type', 'App\Models\Client')
                                ->where('assigneeable_id', $this->model->client_id);
                        })->get()->unique('user_id');
                        break;
                    case('App\Models\Client'):
                        // Get Marketing Advisor when on Client page.
                        $this->assignees = Assignee::whereHas('user', function ($q) {
                            $q->whereHas('teams', function ($t){
                                $t->where('slug', 'marketing-advisor');
                            })->orderBy('first_name', 'asc');
                        })->where('assigneeable_type', get_class($this->model))
                            // Get Marketing Advisors attached to client
                            ->where('assigneeable_id', $this->model->id)
                            ->get()->unique('user_id');
                        break;
                }
            } else { // For Assignees

                // Assignees are assigned to Phases
                switch ( get_class($this->model) ) {
                    case('App\Models\Phase'):
                        // Get Assignees when on Phase page
                        $this->assignees = Assignee::whereHas('user', function ($q) {
                            $q->whereHas('teams', function ($t){
                                $t->where('slug', '!=', 'marketing-advisor');
                            })->orderBy('first_name', 'asc');
                        })->where(function ($w) {
                            // Get Assignees attached to Phase
                            $w->where('assigneeable_type', 'App\Models\Phase')
                                ->where('assigneeable_id', $this->model->id);
                        })->get()->unique('user_id');
                        break;
                    case('App\Models\Project'):
                        // Get Assignees when on Project page
                        $this->assignees = Assignee::whereHas('user', function ($q) {
                            $q->whereHas('teams', function ($t){
                                $t->where('slug', '!=', 'marketing-advisor');
                            })->orderBy('first_name', 'asc');
                        })->where(function ($w) {
                            // Get Assignees attached to project (a marketing advisor)
                            $w->where('assigneeable_type', 'App\Models\Project')
                                ->where('assigneeable_id', $this->model->id);
                        })->orWhere(function ($o) {
                            // Get Assignees attached to phase
                            $o->where('assigneeable_type', 'App\Models\phase')
                                ->whereIn('assigneeable_id', $this->model->phases->pluck('id')->toArray());
                        })->get()->unique('user_id');
                        break;
                    case('App\Models\Client'):
                        // Get Assignees when on Client page
                        // Get all phase ids for each project the client has
                        $phaseIds = [];
                        foreach ( $this->model->projects as $project ) {
                            array_push($phaseIds, $project->phases->pluck('id')->toArray());
                        }

                        $this->assignees = Assignee::whereHas('user', function ($q) {
                            $q->orderBy('first_name', 'asc');
                        })
                            ->where(function ($w) {
                                // Get Assignees attached to phase
                                $w->where('assigneeable_type', 'App\Models\Project')
                                    ->whereIn('assigneeable_id', $this->model->projects->pluck('id')->toArray());
                            })->orWhere(function ($w) use ($phaseIds) {
                                $w->where('assigneeable_type', 'App\Models\Phase')
                                    ->whereIn('assigneeable_id', $phaseIds);
                            })->get()->unique('user_id');
                        break;
                }
            }
        }

        public function assign($user_id) {
            $user = User::find($user_id);
            DB::beginTransaction();

            Assignee::create([
                'user_id'           => $user_id,
                'assigneeable_type' => get_class($this->model),
                'assigneeable_id'   => $this->model->id,
            ]);

            $action = Action::create([
                'client_id' => get_class($this->model) == 'App\Models\Client' ? $this->model->id : $this->model->client_id,
                'user_id' => auth()->user()->id,
                'type' => 'user_assigned',
                'value' => strtolower(str_replace('App\Models\\', '', get_class($this->model))),
                'relation_id' => $user_id,
                'data' => json_encode(['full_name' => $user->full_name]),
                'actionable_type' => get_class($this->model),
                'actionable_id' => $this->model->id,
            ]);

            $this->emit('actionCreated');

            DB::commit();
            $this->mount();
            $this->load();
            $this->emit('assigneesUpdated');
        }

        public function unassign($user_id) {
            Assignee::where('assigneeable_type', get_class($this->model))
                ->where('assigneeable_id', $this->model->id)
                ->where('user_id', $user_id)
                ->firstOrFail()
                ->delete();

            $action = Action::where('type', 'user_assigned')
                ->where('actionable_type', get_class($this->model))
                ->where('actionable_id', $this->model->id)
                ->where('relation_id', $user_id)
                ->get();
            $action->first()->delete();

            $this->emit('actionDeleted');

            $this->mount();
            $this->load();
            $this->emit('assigneesUpdated');
        }

        public function render() {
            return view('livewire.admin.assign-user');
        }
    }
