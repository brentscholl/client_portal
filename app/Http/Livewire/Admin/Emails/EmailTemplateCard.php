<?php

namespace App\Http\Livewire\Admin\Emails;

use App\Mail\NotifyClient;
use App\Models\Email;
use App\Models\User;
use App\Traits\EmailBuilder\EmailDataLoader;
use App\Traits\HasMenu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmailTemplateCard extends Component
{
    use HasMenu, EmailDataLoader;

    public $email_template;

    public $showSentHistory = false;

    public $showClient = false;

    public $email_signature;

    public $client;

    public function stopSchedule() {
       $this->email_template->update(['schedule_email_to_send' => 0]);
        $this->emit('emailTemplateUpdated');
        flash('Email Template Schedule Stopped')->success()->livewire($this);
    }

    public function destroy() {
        $this->email_template->delete();
        $this->emit('emailTemplateDeleted');
        flash('Email Template Deleted')->success()->livewire($this);
    }

    public function sendEmail(){
        DB::beginTransaction();
        $this->email_signature = $this->email_template->email_signature;
        $this->client = $this->email_template->client;

        $this->loadData(json_decode($this->email_template->layout, true), null, $this->email_template->user);

        $recipients = User::hydrate(json_decode($this->email_template->recipients));

        // Create Email for DB record
        Email::create([
            'client_id'         => $this->email_template->client_id,
            'email_template_id' => $this->email_template->id,
            'subject'           => $this->email_template->subject,
            'recipients'        => json_encode($recipients->pluck('email')->toArray()),
            'data'              => json_encode($this->data),
            'sent_date'         => Carbon::now(),
        ]);

        // Queue mail to send
        \Mail::to($recipients)->queue(new NotifyClient($this->data, json_decode($this->email_template->layout, true), $this->email_template->subject));

        DB::commit();

        $this->emit('emailTemplateUpdated');
        flash('Email Sent!')->success()->livewire($this);
    }

    public function showSentHistroy() {
        $this->showSentHistory = ! $this->showSentHistory;
    }


    public function render()
    {
        $object = (array)json_decode($this->email_template->recipients);
        $recipients = User::hydrate($object);
        $recipients = $recipients->flatten();



        return view('livewire.admin.emails.email-template-card', ['recipients' => $recipients]);
    }
}
