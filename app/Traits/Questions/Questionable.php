<?php

    namespace App\Traits\Questions;

    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Project;
    use App\Models\Service;

    trait Questionable
    {
        public $question;

        public $client;

        public $setClient = true;

        public $clients;

        public $project;

        public $setProject = true;

        public $projects = null;

        public $service;

        public $setService = true;

        public $services;

        public $package;

        public $setPackage = true;

        public $setOnboarding = true;

        public $packages;

        public $service_ids = [];

        public $assignable_services = null;

        public $body;

        public $tagline;

        public $question_type = 'detail';

        public $assign_type = 'client';

        public $choices = [null,];

        public $add_file_uploader = false;

        public $assign_to_team = false;

        public $team;

        public $teams;

        public $team_ids = [];

        public $assignable_teams = null;

        public $unique = false;

        public $button_type = 'secondary';

        /**
         * Set client in client select drop down.
         *
         * @param $id
         */
        public function setClient($id) {
            $this->client = Client::find($id);
            $this->projects = $this->client->projects;
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
         * Set service in service select drop down.
         *
         * @param $id
         */
        public function setService($id) {
            $this->service = Service::find($id);
            $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
        }

        /**
         * Add service to the list of services selected
         *
         * @param $service_id
         */
        public function assignService($service_id) {
            array_push($this->service_ids, $service_id);
        }

        /**
         * Remove package from the list of services selected
         *
         * @param $service_id
         */
        public function unassignService($service_id) {
            if ( ($key = array_search($service_id, $this->service_ids)) !== false ) {
                unset($this->service_ids[$key]);
            }
        }

        /**
         * Set package in package select drop down.
         *
         * @param $id
         */
        public function setPackage($id) {
            $this->package = Package::find($id);
        }

        /**
         * Add new choice when add new choice button is clicked
         * (for multi-choice and select question type)
         */
        public function addNewChoice() {
            array_push($this->choices, null);
        }

        /**
         * Remove choice when remove button is clicked
         * (for multi-choice and select question type)
         *
         * @param $i
         */
        public function removeChoice($i) {
            unset($this->choices[$i]);
            // Rebuild Collections
            $newChoicesArray = [];
            foreach ( $this->choices as $choice ) {
                array_push($newChoicesArray, $choice);
            }

            $this->choices = $newChoicesArray;
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
