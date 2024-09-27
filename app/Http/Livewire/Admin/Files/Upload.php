<?php

namespace App\Http\Livewire\Admin\Files;

use App\Traits\HasFileUploader;
use App\Traits\Slideable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Upload extends Component
{
    use HasFileUploader, Slideable;

    public $client_id;

    public $client;

    public $setClient = false;

    public $clients;

    public $model;

    public $is_resource = false;

    public $button_type = 'secondary';

    public $location;

    public function rules() {
        return [
            'files' => ['required', 'max:50000'],
        ];
    }

    protected $messages = [
        'files.size' => 'Cannot upload more than 50MB.',
    ];

    public function mount() {

    }

    public function load() {

    }

    public function uploadFiles() {
        $this->validate();
        DB::beginTransaction();
        // Store files
        $this->addNewFile($this->model, $this->is_resource);

        DB::commit();

        $this->reset([
            'files',
        ]);

        $this->emit('fileUploaded');
        $this->closeSlideout();

        flash('Files Uploaded!')->success()->livewire($this);
    }

    public function render()
    {
        return view('livewire.admin.files.upload', ['button_type']);
    }
}
