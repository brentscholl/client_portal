<?php

    namespace App\Traits\Projects;

    use App\Models\Client;
    use App\Models\Package;
    use App\Models\Service;
    use App\Traits\Urlable;

    trait Projectable
    {
        use Urlable;

        public $project;

        public $client;

        public $setClient = true;

        public $clients = null;

        public $title;

        public $description;

        public $hidden = false;

        public $setService = true;

        public $services;

        public $service;

        public $due_date = null;

        public $setPackage = true;

        public $package_ids = [];

        public $assignable_packages = null;

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
         * Set service in service select drop down.
         *
         * @param $id
         */
        public function setService($id) {
            $this->service = Service::find($id);
            $this->assignable_packages = Package::where('service_id', $this->service->id)->select('service_id', 'id', 'title')->get();
        }

        /**
         * Add package to the list of packages selected
         * @param $package_id
         */
        public function assignPackage($package_id) {
            array_push($this->package_ids, $package_id);
        }

        /**
         * Remove package from the list of packages selected
         * @param $package_id
         */
        public function unassignPackage($package_id) {
            if ( ($key = array_search($package_id, $this->package_ids)) !== false ) {
                unset($this->package_ids[$key]);
            }
        }
    }
