<?php

namespace App\Http\Livewire\Admin\Files;

use App\Traits\HasFileUploader;
use App\Traits\HasMenu;
use Livewire\Component;

class FileCard extends Component
{
    use HasFileUploader, HasMenu;

    public $file;
    public $show_card_icon = true;

    public function render()
    {
        return view('livewire.admin.files.file-card');
    }
}
