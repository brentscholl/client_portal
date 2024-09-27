<?php

namespace App\Http\Livewire\Notifications;

use Illuminate\Support\Str;
use Livewire\Component;

class Item extends Component
{
    public $notification;
    public $linkType;
    public $notification_type;

    /**
     * Update a notification when a user clicks it and actually sees the content relating to the notification.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function mount() {
        $this->notification_type = Str::snake(class_basename($this->notification->type));
    }

    public function see()
    {
        // Update the notification
        if ($this->notification->seen == 0) {
            $notification = auth()->user()->notifications->where('id', $this->notification->id)->first();
            $notification->seen = 1;
            $notification->save();
        }

        return redirect($this->getRoute());
    }

    public function getRoute() {
        switch($this->notification_type){
            case('mentioned'):
                $route = route('admin.' . strtolower($this->notification->data['model']['class']) . 's.show',  ['id' => $this->notification->data['model']['id']] ) . '/?comment=' . $this->notification->data['model']['id'];
                break;
        }
        return $route;
    }

    public function render()
    {
        return view('livewire.notifications.item');
    }
}
