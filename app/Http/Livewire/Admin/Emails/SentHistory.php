<?php

    namespace App\Http\Livewire\Admin\Emails;

    use App\Models\Email;
    use App\Traits\Slideable;
    use Livewire\Component;

    class SentHistory extends Component
    {
        use Slideable;

        public $email_template;

        public $client;

        public $perPage = 10;

        public $button_type = 'secondary';

        public function load() {
        }

        /**
         * Increase Pagination
         */
        public function loadMore() {
            $this->perPage = $this->perPage + 10;
        }

        public function render() {
            if ( $this->email_template ) {
                $emails = Email::where('email_template_id', $this->email_template->id)->with('emailTemplate')->latest()->paginate($this->perPage);
            } else {
                $emails = Email::where('client_id', $this->client->id)->with('emailTemplate')->latest()->paginate($this->perPage);
            }

            return view('livewire.admin.emails.sent-history', ['emails' => $emails]);
        }
    }
