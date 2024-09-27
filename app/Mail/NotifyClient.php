<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyClient extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public $layout;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $layout, $subject)
    {
        $this->data = $data;
        $this->layout = $layout;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->subject);

        return $this->markdown('emails.notify-client');
    }
}
