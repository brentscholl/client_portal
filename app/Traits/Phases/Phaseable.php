<?php

    namespace App\Traits\Phases;

    use App\Traits\Urlable;

    trait Phaseable
    {
        use Urlable;

        public $phase;

        public $project;

        public $title;

        public $description;

        public $due_date = null;

        public $button_type = 'secondary';
    }
