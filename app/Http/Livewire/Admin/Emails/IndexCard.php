<?php

namespace App\Http\Livewire\Admin\Emails;

use App\Models\Email;
use App\Models\EmailTemplate;
use App\Traits\Collapsible;
use Livewire\Component;
use Livewire\WithPagination;

class IndexCard extends Component
{
    use Collapsible, WithPagination;

    public $client;

    public $showClient = false;

    public $filter = 'all';

    public $perPage = 5;

    public $listeners = ['emailTemplateUpdated' => '$refresh', 'emailTemplateCreated' => '$refresh', 'emailTemplateDeleted' => '$refresh'];

    public function mount() {
    }

    /**
     * Change the filter on the index card
     * @param $filter
     */
    public function filter($filter) {
        $this->filter = $filter;
        $this->mount();
    }

    /**
     * Increase Pagination
     */
    public function loadMore() {
        $this->perPage = $this->perPage + 5;
    }

    public function render()
    {
        $email_templates = EmailTemplate::query();

        $email_templates = $email_templates->where('client_id', $this->client->id);

        // Filter ----
        $emails = null;
        if($this->filter == 'history'){
            $emails = Email::where('client_id', $this->client->id)
                ->with('emailTemplate')
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        }elseif ( $this->filter != 'all' ) {
            switch ( $this->filter ) {
                case('active'): // Get active schedules
                    $email_templates = $email_templates->where('schedule_email_to_send', 1);
                    break;
                case('drafts'): // Get draft email templates
                    $email_templates = $email_templates->where('is_draft', 1);
                    break;
            }
        }


        $email_templates = $email_templates->with([
            'client:id,title',
            'user:id,first_name,last_name',
            'emails'
        ])->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin.emails.index-card', ['email_templates' => $email_templates, 'emails' => $emails]);
    }
}
