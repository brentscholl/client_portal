<?php

namespace App\Http\Livewire\Notifications;

use App\Traits\Slideable;
use Livewire\Component;

class Nav extends Component
{
    use Slideable;
    public $hasUnread = false;

    public function mount() {
        if(count(auth()->user()->unreadNotifications)){
            $this->hasUnread = true;
        }
    }

    public function load() {
        $this->read();
    }

    public function read() {
        auth()->user()->unreadNotifications->markAsRead();
        $this->hasUnread = false;
    }

    public function render()
    {
        return view('livewire.notifications.nav');
    }
}
