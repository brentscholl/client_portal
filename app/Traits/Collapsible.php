<?php

    namespace App\Traits;

    trait Collapsible
    {
        public $cardOpened = false;

        /**
         * Opens the index card
         */
        public function openCard() {
            $this->cardOpened = true;
        }

    }
