<?php

    namespace App\Traits\Tutorials;

    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Service;

    trait Tutorialable
    {
        public $tutorial;

        public $client;

        public $setClient = true;

        public $clients;

        public $client_ids = [];

        public $assignable_clients = null;

        public $service;

        public $setService = true;

        public $services;

        public $package;

        public $setPackage = true;

        public $packages;

        public $title;

        public $body;

        public $image;

        public $video_url;

        public $assign_type = 'client';

        public $unique = false;

        public $button_type = 'secondary';

        /**
         * Set client in client select drop down.
         *
         * @param $id
         */
        public function setService($id) {
            $this->service = Service::find($id);
            $this->packages = Package::where('service_id', $this->service->id)->orderBy('title', 'asc')->select('title', 'id')->get();
        }

        /**
         * Add client to the list of clients selected
         *
         * @param $client_id
         */
        public function assignClient($client_id) {
            array_push($this->client_ids, $client_id);
        }

        /**
         * Remove client from the list of clients selected
         *
         * @param $client_id
         */
        public function unassignClient($client_id) {
            if ( ($key = array_search($client_id, $this->client_ids)) !== false ) {
                unset($this->client_ids[$key]);
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
    }
