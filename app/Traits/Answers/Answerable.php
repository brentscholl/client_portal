<?php

    namespace App\Traits\Answers;

    use App\Models\Client;

    trait Answerable
    {
        public $client_id;

        public $client;

        public $setClient = false;

        public $clients;

        public $question;

        public $question_answer;

        public $answer;

        public $choices = [];

        public $availableChoices;

        public $button_type = 'secondary';

        /**
         * Set client in client select drop down.
         *
         * @param $id
         */
        public function setClient($id) {
            $this->client = Client::find($id);
        }
    }
