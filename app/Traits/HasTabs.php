<?php

    namespace App\Traits;

    trait HasTabs
    {
        public $tab = 'details';

        public function showTab($tab) {
            $this->tab = $tab;
        }
    }
