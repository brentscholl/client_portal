<?php

    namespace App\Traits;

    trait HasMenu
    {
        public $showMenuContent = false;

        /**
         * Open the menu and show contents
         */
        public function showMenuContent() {
            $this->showMenuContent = true;
        }

    }
