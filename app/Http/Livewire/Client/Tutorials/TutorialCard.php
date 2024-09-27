<?php

namespace App\Http\Livewire\Client\Tutorials;

use Livewire\Component;

class TutorialCard extends Component
{
    public $tutorial;
    public $embed_id;

    public $play = false;

    public function mount() {
        $this->embed_id = str_replace('/share/', '', parse_url($this->tutorial->video_url)['path']);
    }

    public function render()
    {
        return view('livewire.client.tutorials.tutorial-card');
    }
}
