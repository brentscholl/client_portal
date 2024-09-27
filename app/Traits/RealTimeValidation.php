<?php

    namespace App\Traits;

    trait RealTimeValidation
    {
        /**
         * When a value is updated, validate the value
         * @param $value
         */
        public function updated($value) {
            $this->resetErrorBag($value);
            $this->validateOnly($value);
        }
    }
