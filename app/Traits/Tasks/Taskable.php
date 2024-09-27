<?php

    namespace App\Traits\Tasks;

    use App\Models\Client;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Task;
    use App\Traits\Urlable;

    trait Taskable
    {
        use Urlable;

        public $task;

        public $client;

        public $setClient = true;

        public $clients = null;

        public $project;

        public $setProject = true;

        public $projects = null;

        public $phase = null;

        public $setPhase = true;

        public $title;

        public $description;

        public $due_date;

        public $task_type = 'detail';

        public $add_file_uploader = false;

        public $hidden = false;

        public $assign_to_team = false;

        public $team;

        public $teams;

        public $team_ids = [];

        public $assignable_teams = null;

        public $add_dependable_task = false;

        public $dependable_task;

        public $dependable_tasks = null;

        public $priority = 1;

        public $button_type = 'secondary';

        /**
         * Set client in client select drop down.
         *
         * @param $id
         */
        public function setClient($id) {
            $this->client = Client::find($id);
            $this->projects = $this->client->projects;
            $this->dependable_tasks = $this->client->tasks;
        }

        /**
         * Set project in project select drop down.
         *
         * @param $id
         */
        public function setProject($id) {
            $this->project = Project::with(['phases', 'tasks'])->find($id);
        }

        /**
         * Set phase in phase select drop down.
         *
         * @param $id
         */
        public function setPhase($id) {
            $this->phase = Phase::find($id);
        }

        /**
         * Set Dependable Task in Dependable Task drop down.
         *
         * @param $id
         */
        public function setDependableTask($id) {
            $this->dependable_task = Task::find($id);
        }

        /**
         * Add team to the list of teams selected
         *
         * @param $team_id
         */
        public function assignTeam($team_id) {
            array_push($this->team_ids, $team_id);
        }

        /**
         * Remove team from the list of teams selected
         *
         * @param $team_id
         */
        public function unassignTeam($team_id) {
            if ( ($key = array_search($team_id, $this->team_ids)) !== false ) {
                unset($this->team_ids[$key]);
            }
        }
    }
