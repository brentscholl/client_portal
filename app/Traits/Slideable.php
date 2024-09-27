<?php

    namespace App\Traits;

    trait Slideable
    {
        public $slideout_open = false;

        /**
         * Open the slideout and load the data needed
         */
        public function openSlideout() {
            $this->slideout_open = true;
            $this->load();
        }

        /**
         * Close the slideout
         */
        public function closeSlideout() {
            $this->dispatchBrowserEvent('close-slideout');
        }
    }
