<?php

    namespace App\Traits\EmailBuilder;

    use Carbon\Carbon;

    trait EmailScheduler
    {
        public $email_template;

        public $email_schedule = [
            'set_send_date'    => true,
            'send_date'        => null,
            'send_date_number' => null,
            'send_date_day'    => null,
            'send_date_week'   => null,
            'repeat'           => 'does-not-repeat',
            'repeat_custom'    => [
                'repeats_every'      => [
                    'item' => 1,
                    'type' => 'day',
                ],
                'repeats_weekly_on'  => [
                    'sunday'    => false,
                    'monday'    => false,
                    'tuesday'   => false,
                    'wednesday' => false,
                    'thursday'  => false,
                    'friday'    => false,
                    'saturday'  => false,
                ],
                'repeats_monthly_on' => null,
                'ends'               => 'never',
                'end_date'           => null,
            ],
        ];

        /**
         * Sets the date input field in the builder to correct day when setting email to send later
         */
        public function setDefaultEmailScheduleDate() {
            $send_date = Carbon::now();
            $this->email_schedule['send_date'] = $send_date->isoFormat('MMMM D, YYYY'); // Friday, January 29th, 2021
            $this->email_schedule['send_date_number'] = $send_date->isoFormat('D'); // 29
            $this->email_schedule['send_date_day'] = $send_date->isoFormat('dddd'); // Friday
            $this->email_schedule['send_date_week'] = $send_date->weekOfMonth; // 5
        }

        /**
         * When editing an email template, this will set the fields in the builder.
         */
        public function setCurrentEmailSchedule() {
            $this->email_schedule = [
                'set_send_date'    => $this->email_template->set_send_date,
                'send_date'        => $this->email_template->send_date ? $this->email_template->send_date->isoFormat('MMMM D, YYYY'): null,
                'send_date_number' => $this->email_template->send_date ? $this->email_template->send_date->isoFormat('D') : null,
                'send_date_day'    => $this->email_template->send_date ? $this->email_template->send_date->isoFormat('dddd') : null,
                'send_date_week'   => $this->email_template->send_date ? $this->email_template->send_date->weekOfMonth : null,
                'repeat'           => $this->email_template->repeat,
                'repeat_custom'    => [
                    'repeats_every'      => [
                        'item' => $this->email_template->repeat_every_item,
                        'type' => $this->email_template->repeat_every_type,
                    ],
                    'repeats_weekly_on'  => json_decode($this->email_template->repeats_weekly_on),
                    'repeats_monthly_on' => $this->email_template->repeats_monthly_on,
                    'ends'               => $this->email_template->ends,
                    'end_date'           => $this->email_template->end_date ? $this->email_template->end_date->isoFormat('MMMM D, YYYY') : null,
                ],
            ];
        }
    }
