<?php

    namespace App\Http\Livewire\Admin\Emails;

    use App\Mail\NotifyClient;
    use App\Models\Action;
    use App\Models\Email;
    use App\Models\EmailTemplate;
    use App\Models\Phase;
    use App\Models\Project;
    use App\Models\Question;
    use App\Models\Task;
    use App\Models\Tutorial;
    use App\Models\User;
    use App\Traits\EmailBuilder\EmailDataLoader;
    use App\Traits\EmailBuilder\EmailScheduler;
    use App\Traits\Slideable;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Support\Facades\DB;
    use Livewire\Component;

    class Create extends Component
    {
        use Slideable, EmailDataLoader, EmailScheduler;

        public $client;

        public $email_template;

        public $is_editing = false;

        public $recipients;

        public $assignable_recipients = [];

        public $recipient_ids = [];

        public $recipientModalOpen = false;

        public $subject;

        public $email_signature = 'user';

        public $schedule_email_to_send = false;

        public $layout = [];

        public $saving_as_draft = false;

        public $saved = false;

        public $button_type = 'secondary';

        public $components = [
            'tasks'             => [
                'layout' => 'tasks',
                'title'  => 'Tasks',
                'inputs' => [
                    'project'  => null,
                    'projects' => null,
                    'phase'    => null,
                    'phases'   => null,
                    'statuses' => [
                        'pending'     => true,
                        'in-progress' => false,
                        'completed'   => false,
                        'on-hold'     => false,
                        'canceled'    => false,
                    ],
                ],
            ],
            'projects'          => [
                'layout' => 'projects',
                'title'  => 'Projects',
                'inputs' => [
                    'statuses' => [
                        'pending'     => false,
                        'in-progress' => false,
                        'completed'   => false,
                        'on-hold'     => false,
                        'canceled'    => false,
                    ],
                ],
            ],
            'questions'         => [
                'layout' => 'questions',
                'title'  => 'Questions',
                'inputs' => [
                    'project'            => null,
                    'projects'           => null,
                    'filter'             => 'all',
                    'include_onboarding' => true,
                ],
            ],
            'tutorials'         => [
                'layout' => 'tutorials',
                'title'  => 'Tutorials',
                'inputs' => [
                    'project'  => null,
                    'projects' => null,
                ],
            ],
            'single_task'       => [
                'layout' => 'single_task',
                'title'  => 'Task',
                'inputs' => [
                    'projects'   => null,
                    'project'    => null,
                    'phases'     => null,
                    'phase'      => null,
                    'tasks'      => null,
                    'task_id'    => null,
                    'task_title' => null,
                    'task'       => null,
                ],
            ],
            'single_project'    => [
                'layout' => 'single_project',
                'title'  => 'Project',
                'inputs' => [
                    'projects' => null,
                    'project'  => null,
                ],
            ],
            'single_question'   => [
                'layout' => 'single_question',
                'title'  => 'Question',
                'inputs' => [
                    'include_onboarding' => true,
                    'projects'           => null,
                    'project'            => null,
                    'questions'          => null,
                    'question_id'        => null,
                    'question_body'      => null,
                    'question'           => null,
                ],
            ],
            'single_tutorial'   => [
                'layout' => 'single_tutorial',
                'title'  => 'Tutorial',
                'inputs' => [
                    'projects'       => null,
                    'project'        => null,
                    'tutorials'      => null,
                    'tutorial_id'    => null,
                    'tutorial_title' => null,
                    'tutorial'       => null,
                ],
            ],
            'message'           => [
                'layout' => 'message',
                'title'  => 'Message',
                'inputs' => [
                    'message' => '',
                ],
            ],
            'alert'             => [
                'layout' => 'alert',
                'title'  => 'Alert',
                'inputs' => [
                    'message' => '',
                ],
            ],
            'link_to_dashboard' => [
                'layout' => 'link_to_dashboard',
                'title'  => 'Link to Dashboard',
                'inputs' => [],
            ],
        ];

        public function mount()
        {
        }

        public function load()
        {
            if (! $this->is_editing) {
                $this->email_template = null;
                $this->reset([
                    'email_template',
                    'recipient_ids',
                    'recipientModalOpen',
                    'subject',
                    'email_signature',
                    'schedule_email_to_send',
                    'layout',
                    'saving_as_draft',
                    'saved',
                ]);
            }
            // Set recipients
            $this->recipients = User::where('id', $this->client->primary_contact)->get();

            // Add in the default message component
            $this->addComponent('message', 0);

            // Set the default email Schedule date
            $this->setDefaultEmailScheduleDate();

            // If editing an existing email template, load in all correct components and data
            if ($this->email_template) { // We are editing
                $this->client = $this->email_template->client;

                $this->subject = $this->email_template->subject;

                if($this->email_template->recipients) {
                    $object = json_decode($this->email_template->recipients);
                    $recipient_ids = array_column($object, 'id');
                    $this->recipients = User::whereIn('id', $recipient_ids)->get();
                }

                $this->message = $this->email_template->message;

                $this->email_signature = $this->email_template->email_signature;

                $this->schedule_email_to_send = $this->email_template->schedule_email_to_send;

                $this->setCurrentEmailSchedule();

                $this->layout = json_decode($this->email_template->layout, true);
            }
            $this->loadData($this->layout);
        }

        /**
         * Validation rules
         *
         * @return \string[][]
         */
        public function rules()
        {
            return [
                'client'                                   => ['required'],
                'recipients'                               => ['required'],
                'subject'                                  => ['required', 'min:2', 'max:100'],
                'layout.*.single_task.inputs.task'         => ['sometimes', 'required'],
                'layout.*.single_project.inputs.project'   => ['sometimes', 'required'],
                'layout.*.single_question.inputs.question' => ['sometimes', 'required'],
                'layout.*.single_tutorial.inputs.tutorial' => ['sometimes', 'required'],
                'layout.*.alert.inputs.message'            => ['sometimes', 'required'],
            ];
        }

        /**
         * Custom Validation Messages
         *
         * @var string[]
         */
        protected $messages = [
            'recipients.required'                             => 'You need at least 1 recipient.',
            'layout.single_task.inputs.task.required'         => 'Please select a Task.',
            'layout.single_project.inputs.project.required'   => 'Please select a Project.',
            'layout.single_question.inputs.question.required' => 'Please select a Question.',
            'layout.single_tutorial.inputs.tutorial.required' => 'Please select a Tutorial.',
            'layout.text.inputs.message.required'             => 'Text can not be blank if included in layout.',
            'layout.alert.inputs.message.required'            => 'Alert can not be blank if included in layout.',
        ];

        /**
         * Grab client reps when adding recipient
         */
        public function openRecipientModal()
        {
            $this->assignable_recipients = User::where('client_id', $this->client->id)->orderBy('first_name', 'asc')->get();
            $this->recipient_ids = $this->recipients->pluck('id')->toArray();
            $this->recipientModalOpen = true;
        }

        /**
         * Assign Recipient
         *
         * @param \App\Models\User $user
         */
        public function assignRecipient(User $user)
        {
            $this->recipients->add($user);
            $this->recipient_ids = $this->recipients->pluck('id')->toArray();
        }

        public function assignAllRecipient()
        {
            $this->recipients = $this->assignable_recipients;
            $this->recipient_ids = $this->assignable_recipients->pluck('id')->toArray();
        }

        /**
         * Assign Recipient
         */
        public function unassignRecipient($id)
        {
            $key = $this->recipients->search(function ($item) use ($id) {
                return $item['id'] == $id;
            });

            $this->recipients->pull($key);
            $this->recipient_ids = $this->recipients->pluck('id')->toArray();
        }

        /**
         * Add component to layout and load its data
         *
         * @param $component
         * @param $i
         */
        public function addComponent($component, $i)
        {
            array_push($this->layout, $this->components[$component]);
            $this->loadData($this->layout, $i);
            $this->layout = $this->data['layout'];
        }

        /**
         * Remove component from layout
         *
         * @param $i
         */
        public function removeComponent($i)
        {
            unset($this->layout[$i]);
            $this->layout = array_values($this->layout); // reindex array
        }

        /**
         * Change the order of the layout components
         * (on move button click)
         *
         * @param $list
         */
        public function moveComponent($direction, $item)
        {
            $out = array_splice($this->layout, $item, 1);
            array_splice($this->layout, $direction == 'up' ? $item - 1 : $item + 1, 0, $out);
            $this->dispatchBrowserEvent('layoutOrderChanged');
        }

        /**
         * Update layout data when component is updated
         *
         * @param $field
         * @param $newValue
         */
        public function updated($field, $newValue)
        {
            $this->saved = false;
            if ($field == 'email_schedule.send_date') {
                $this->dateUpdated($newValue, 'start');
            } elseif ($field == 'email_schedule.repeat_custom.end_date') {
                $this->dateUpdated($newValue, 'end');
            }
            $fieldArray = explode(".", $field);
            if ($fieldArray[0] == 'layout') {
                $this->loadData($this->layout, $fieldArray[1]);
            } else {
                $this->loadData($this->layout);
            }
            $this->layout = $this->data['layout'];
        }

        /**
         * Fires when a Froala Editor is updated
         *
         * @param $i
         * @param $value
         */
        public function editorValue($i, $value)
        {
            $this->layout[$i]['inputs']['message'] = $value;
        }

        /**
         * Set project in project select drop down.
         *
         * @param $id
         */
        public function setProject($id, $i)
        {
            if ($id) {
                $project = Project::with([
                    'phases' => function ($p) {
                        $p->select(['id', 'project_id', 'title']);
                    },
                ])->select(['id', 'title'])->find($id);
                $this->layout[$i]['inputs']['project'] = $project->toArray();
            } else {
                $this->layout[$i]['inputs']['project'] = null;
            }
            $this->loadData($this->layout, $i);
        }

        /**
         * Set phase in phase select drop down.
         *
         * @param $id
         */
        public function setPhase($id, $i)
        {
            if ($id) {
                $this->layout[$i]['inputs']['phase'] = Phase::select([
                    'id',
                    'project_id',
                    'title',
                ])->find($id)->toArray();
            } else {
                $this->layout[$i]['inputs']['phase'] = null;
            }
            $this->loadData($this->layout, $i);
        }

        /**
         * Set task in task select drop down.
         *
         * @param $id
         */
        public function setTask($id, $title, $i)
        {
            $this->layout[$i]['inputs']['task_id'] = $id;
            $this->layout[$i]['inputs']['task_title'] = $title;
            $this->loadData($this->layout, $i);
        }

        /**
         * Set question in question select drop down.
         *
         * @param $id
         */
        public function setQuestion($id, $body, $i)
        {
            $this->layout[$i]['inputs']['question_id'] = $id;
            $this->layout[$i]['inputs']['question_body'] = $body;
            $this->loadData($this->layout, $i);
        }

        /**
         * Set tutorial in tutorial select drop down.
         *
         * @param $id
         */
        public function setTutorial($id, $title, $i)
        {
            $this->layout[$i]['inputs']['tutorial_id'] = $id;
            $this->layout[$i]['inputs']['tutorial_title'] = $title;
            $this->loadData($this->layout, $i);
        }

        /**
         * Called when the datepicker changes
         * When the date changes we need to update our properties to correctly show the date info in the other elements on the page
         *
         * @param $value
         * @param $scheduleIndex
         * @param $dateType
         */
        public function dateUpdated($value = null, $dateType = null)
        {
            $date = Carbon::parse($value);
            if ($dateType == 'start') {
                // Lets say they pick Jan 29th, 2021
                $this->email_schedule['send_date'] = $date->isoFormat('MMMM D, YYYY'); // 2021-01-29
                $this->email_schedule['send_date_number'] = $date->isoFormat('D'); // 29
                $this->email_schedule['send_date_day'] = $date->isoFormat('dddd'); // Friday
                $this->email_schedule['send_date_week'] = $date->weekOfMonth; // 5
            }
            if ($dateType == 'end') {
                // Lets say they pick Jan 29, 2021
                $this->email_schedule['repeat_custom']['end_date'] = $date->isoFormat('MMMM D, YYYY'); // 2021-01-29
            }
        }

        /**
         * Create or edit an email template to database
         *
         * @param false $is_editing
         * @param false $is_draft
         */
        public function createEmailTemplate($is_editing = false, $is_draft = false)
        {
            if ($this->email_schedule['repeat_custom']['ends'] == 'never') {
                $end_date = date('Y-m-d', strtotime($this->email_schedule['send_date'] ? $this->email_schedule['send_date'] : now()->format('Y-m-d')));
            } else {
                $end_date = date('Y-m-d', strtotime($this->email_schedule['repeat_custom']['end_date']));
            }
            $attr = [
                'client_id'              => $this->client->id,
                'user_id'                => auth()->user()->id,
                'subject'                => $this->subject,
                'recipients'             => json_encode($this->recipients),
                'email_signature'        => $this->email_signature,
                'schedule_email_to_send' => $this->schedule_email_to_send,
                'set_send_date'          => $this->email_schedule['set_send_date'],
                'send_date'              => $this->email_schedule['set_send_date'] ? date('Y-m-d', strtotime($this->email_schedule['send_date'])) : now()->format('Y-m-d'),
                'repeat'                 => $this->email_schedule['repeat'],
                'repeats_every_item'     => $this->email_schedule['repeat_custom']['repeats_every']['item'],
                'repeats_every_type'     => $this->email_schedule['repeat_custom']['repeats_every']['type'],
                'repeats_weekly_on'      => json_encode($this->email_schedule['repeat_custom']['repeats_weekly_on']),
                'repeats_monthly_on'     => $this->email_schedule['repeat_custom']['repeats_monthly_on'],
                'ends'                   => $this->email_schedule['repeat_custom']['ends'],
                'end_date'               => $end_date,
                'layout'                 => json_encode($this->layout),
                'is_draft'               => $is_draft ? 1 : 0,
            ];

            if ($is_editing) {
                $this->email_template->update($attr);
                $this->emit('emailTemplateUpdated');

                // Save action
                $start_date = \Illuminate\Support\Carbon::now()->subHour();
                $action = Action::where('type', 'email_template_updated')
                    ->where('created_at', '>=', $start_date)
                    ->where('created_at', '<=', Carbon::now())
                    ->where('actionable_type', 'App\Models\EmailTemplate')
                    ->where('actionable_id', $this->email_template->id)
                    ->firstOrNew();

                $action->fill([
                    'client_id'       => $this->client->id,
                    'user_id'         => auth()->user()->id,
                    'type'            => 'email_template_updated',
                    'body'            => $this->subject,
                    'actionable_type' => get_class($this->email_template),
                    'actionable_id'   => $this->email_template->id,
                ]);

                $action->save();
            } else {
                $this->email_template = EmailTemplate::create($attr);
                $this->emit('emailTemplateCreated');
            }
        }

        /**
         * Save email as draft in database (does not send)
         */
        public function saveDraft()
        {
            $this->saving_as_draft = true;
            DB::beginTransaction();
            $this->createEmailTemplate($this->email_template ? true : false, true);
            DB::commit();

            if ($this->is_editing) {
                flash('Email Template Saved')->success()->livewire($this);
            } else {
                flash('Draft Saved')->success()->livewire($this);
            }
            $this->saved = true;
            $this->saving_as_draft = false;
        }

        /**
         * Saves in database and send to recipients
         */
        public function sendEmail()
        {
            $this->saving_as_draft = true;
            $this->validate();

            DB::beginTransaction();

            // Create email template
            $this->createEmailTemplate($this->email_template ? true : false, false);

            DB::commit();

            // Send email
            if (! $this->schedule_email_to_send) {
                // Send immediately, no schedule
                $this->createEmail($this->email_template);
                flash('Email sent!')->success()->livewire($this);
                $action_value = 'sent';
            } else {
                // There is a schedule, check if we should send immediately or not
                if ($this->email_schedule['set_send_date']) {
                    // Send later according to schedule
                    flash('Email is scheduled to send on '.$this->email_schedule['send_date'].' at '.config('clientportal.email-scheduler-send-time-human'))->success()->livewire($this);
                    $action_value = 'scheduled';
                } else {
                    // Send Immediately, no send date
                    $this->createEmail($this->email_template);
                    flash('Email sent!')->success()->livewire($this);
                    flash('Schedule activated!')->success()->livewire($this);
                    $action_value = 'scheduled_sent';
                }
            }

            $this->closeSlideout();
            $this->emit('emailTemplateSent');
            $action = Action::create([
                'client_id'       => $this->client->id,
                'user_id'         => auth()->user()->id,
                'type'            => 'email_template_created',
                'value'           => $action_value,
                'body'            => $this->subject,
                'relation_id'     => $this->email_template->id,
                'actionable_type' => 'App\Models\Client',
                'actionable_id'   => $this->email_template->client_id,
            ]);

            $this->emit('actionCreated');

            $this->reset([
                'email_template',
                'recipient_ids',
                'recipientModalOpen',
                'subject',
                'email_signature',
                'schedule_email_to_send',
                'layout',
                'saving_as_draft',
                'saved',
            ]);
            $this->saving_as_draft = false;
        }

        /**
         * Creates an email record and sends email.
         */
        public function createEmail($email_template)
        {
            DB::beginTransaction();

            // Create Email for DB record
            Email::create([
                'client_id'         => $this->client->id,
                'email_template_id' => $email_template->id,
                'subject'           => $this->subject,
                'recipients'        => json_encode($this->recipients->pluck('email')->toArray()),
                'data'              => json_encode($this->data),
                'sent_date'         => Carbon::now(),
            ]);

            // Queue mail to send
            \Mail::to($this->recipients)->queue(new NotifyClient($this->data, $this->layout, $this->subject));

            DB::commit();
        }

        /**
         * Reset inputs and close
         */
        public function cancel()
        {
            $this->closeSlideout();
            $this->reset([
                'recipient_ids',
                'recipientModalOpen',
                'subject',
                'email_signature',
                'schedule_email_to_send',
                'layout',
                'saving_as_draft',
                'saved',
            ]);
            $this->emit('emailTemplateUpdated');
        }

        public function render()
        {
            return view('livewire.admin.emails.create');
        }
    }
