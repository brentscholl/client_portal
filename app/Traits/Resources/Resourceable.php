<?php

    namespace App\Traits\Resources;

    use App\Models\File;
    use App\Traits\Urlable;

    trait Resourceable
    {

        public $resource;

        public $type = 'text';

        public $label;

        public $tagline;

        public $value;

        public $model;

        public $modalOpen = true;

        public $assignable_files = [];

        public $assigned_file;

        public $uploaded_file;

        public $selected_file;

        public $uploaded_new_file = true;

        public $button_type = 'secondary';


        public function updatedType($value) {
            if ( $value == 'file' ) {
                $this->assignable_files = File::whereHas('resources')->orWhere(function($q){
                    $q->whereDoesntHave('resources')->whereDoesntHave('tasks')->whereDoesntHave('answers');
                })->get();
            }
        }

        public function assignFile($id) {
            $this->selected_file = File::find($id);
            $this->uploaded_new_file = false;
        }

        public function unassignFile() {
            $this->selected_file = null;
        }
    }
