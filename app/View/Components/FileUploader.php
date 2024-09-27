<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FileUploader extends Component
{

    public $existingFiles;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($existingFiles)
    {
        $this->existingFiles = $existingFiles;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.file-uploader');
    }
}
