<?php

namespace App\Http\Livewire\Admin\Emails;

use App\Models\Email;
use App\Models\EmailTemplate;
use App\Traits\Slideable;
use Livewire\Component;

class HistoryItem extends Component
{
    use Slideable;

    public $email;

    public $data;

    public $layout;

    public $loop_last;

    public $email_template;

    public function mount(Email $email) {
        $this->email = $email;
        $this->data = json_decode($this->email->data, true);
        $this->email_template = $email->emailTemplate;
        $this->layout = json_decode($this->email_template->layout, true);
    }

    public function load() {

    }

    public function render()
    {
        return view('livewire.admin.emails.history-item');
    }
}
