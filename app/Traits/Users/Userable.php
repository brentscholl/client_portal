<?php

    namespace App\Traits\Users;

    use App\Models\Client;
    use App\Models\Team;

    trait Userable
    {
        public $client;

        public $setClient = true;

        public $clients = null;

        public $first_name;

        public $last_name;

        public $email;

        public $email_confirmation;

        public $password;

        public $phone;

        public $position;

        public $user_type = 'basic';

        public $team;

        public $teams;

        public $team_ids = [];

        public $assignable_teams = null;

        public $user;

        public $email_new_user = true;

        public $button_type = 'secondary';

        /**
         * Set client in client select drop down.
         *
         * @param $id
         */
        public function setClient($id) {
            $this->client = Client::find($id);
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
