<?php
    $borderColor = $email_template->is_draft ? "border-gray-200" : ($email_template->schedule_email_to_send ? "border-gray-200 shadow-md pulse-border-secondary" : "border-secondary-500");
?>
<div class="relative rounded-lg border {{ $borderColor }} bg-white pr-6 pl-8 py-5 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border {{ $borderColor }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.email class="h-4 w-4 text-gray-500"/>
    </div>

    @if($email_template->is_draft)
        <span class="flex flex-shrink-0 space-x-1 items-center absolute left-2 inline-block px-1 py-0.5 focus:outline-none text-gray-400 text-xs font-medium bg-white" style="top: -0.90em;">
            <span>Draft</span>
        </span>
    @elseif($email_template->schedule_email_to_send)
        <span class="flex flex-shrink-0 space-x-1 items-center absolute left-2 inline-block px-1 py-0.5 focus:outline-none text-secondary-400 text-xs font-medium bg-white" style="top: -0.90em;">
            <span>Active Schedule</span>
        </span>
    @endif

    <div class="flex items-center space-x-3">
        <div class="flex-1 min-w-0 space-y-3">
            @if($showClient)
                <p class="flex w-full text-xs text-left text-gray-500 truncate inline-flex items-center">
                <span class="flex items-center">
                    <svg class="h-6 w-6 mr-2 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <a tooltip="View Client" href="{{ route('admin.clients.show', $email_template->client->id) }}" class="hover:text-secondary-500">{{ $email_template->client->title }}</a>
                </span>
                </p>
            @endif
            @if($email_template->subject)
                <p class="text-sm font-medium text-gray-900 transition-all">
                    {{ $email_template->subject }}
                </p>
            @else
                <p class="text-sm font-medium text-gray-400 transition-all">
                    No Subject
                </p>
            @endif
        </div>
        <div class="flex space-x-3 items-center">
            @if(! $email_template->is_draft)
                <x-modal
                    wire:click="sendEmail"
                    type="send"
                    triggerText="Send Email"
                    triggerClass="flex space-x-2 items-center text-xs border w-full text-left px-2 py-2 rounded-md text-gray-500 hover:bg-secondary-100 hover:text-secondary-500"
                    modalTitle="Send Email"
                >
                    <x-slot name="triggerText">
                        <x-svg.send class="h-3 w-3"/>
                        <span>Send</span>
                    </x-slot>
                    Are you sure you want to send this Email Template?<br><br>
                    <div class="rounded-md p-3 bg-gray-100 mt-4">
                        <strong>Recipients:</strong>
                        <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                            @foreach($recipients as $recipient)
                                <li>{{ $recipient->full_name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </x-modal>
            @endif
            @livewire('admin.emails.create', ['email_template' => $email_template, 'is_editing' => true, 'client' => $email_template->client], key('email_template-edit-' . $email_template->id))
        </div>
    </div>
    <div class="flex space-x-4 justify-between mt-4">
        <p class="text-xs text-gray-400">
            Created by:
            <a tooltip="View User" href="{{ route('admin.users.show', [$email_template->user->id]) }}" class="font-medium text-gray-600 hover:text-secondary-500 s-transition">{{ $email_template->user->full_name }}</a>
        </p>
        <div class="flex space-x-2 items-center">
            <p class="text-xs text-gray-400">{{ Str::plural('Recipient', $recipients->count()) }}:</p>
            @foreach($recipients as $recipient)
                <a tooltip="View User" href="{{ route('admin.users.show', $recipient->id) }}" class="text-xs rounded-full py-0.5 px-1.5 bg-gray-100 text-gray-500 hover:bg-secondary-500 hover:text-white">
                    {{ $recipient->full_name }}
                </a>
            @endforeach
        </div>

    </div>

    @if($email_template->schedule_email_to_send)
        <div class="flex space-x-3 rounded-md text-gray-600 bg-gray-50 shadow-inner border border-dashed border-2 p-3 mt-4">
            <x-svg.clock class="h-5 w-5"/>
            <div class="flex flex-col space-y-3 flex-grow-1 w-full">

                <div class="flex space-x-3 justify-between">
                    @if($email_template->repeat !== 'does-not-repeat')
                        <div class="flex flex-col space-y-1 text-xs">
                            <span class="flex space-x-2 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span>Repeats:</span>
                            </span>
                            <span>
                                @switch($email_template->repeat)
                                    @case('daily')
                                    Daily
                                    @break
                                    @case('weekly')
                                    Weekly on {{ $email_template->send_date->isoFormat('dddd') }}
                                    @break
                                    @case('monthly')
                                    Monthly on
                                    the {{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($email_template->send_date->weekOfMonth) }} {{ $email_template->send_date->isoFormat('dddd') }}
                                    @break
                                    @case('weekday')
                                    Every weekday (Monday to Friday)
                                    @break
                                    @case('custom')
                                    <span>{{ $email_template->repeats_every_item }} {{ Str::plural($email_template->repeats_every_type, $email_template->repeats_every_item) }} on</span>
                                    @if($email_template->repeats_every_type == 'week')
                                        <span class="flex space-x-2 mt-2">
                                            @foreach(json_decode($email_template->repeats_weekly_on) as $key => $value)
                                                @if($value)
                                                    <span class="border rounded-full py-0.5 px-2 text-xs">{{ ucwords($key) }}</span>
                                                @endif
                                            @endforeach
                                        </span>
                                    @endif
                                    @if($email_template->repeats_every_type == 'month')
                                        @switch($email_template->repeats_monthly_on)
                                            @case('date')
                                            <span>on the {{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($email_template->send_date->isoFormat('D')) }}
                                                </span>
                                            @break
                                            @case('day')
                                            <span>
                                            on the {{ (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($email_template->send_date->weekOfMonth) }} {{ $email_template->send_date->isoFormat('dddd') }}
                                        </span>
                                            @break
                                        @endswitch
                                    @endif
                                    @break
                                @endswitch
                            </span>
                        </div>
                    @endif
                    @if($email_template->ends == 'ends_on')
                        <div tooltip="Schedule will send on the end date if  interval lands on end date. However, schedule will no longer send after the end date" class="flex flex-col space-y-2 text-xs">
                            <span>Schedule ends:</span>
                            <span>{{ $email_template->end_date->format('M d, Y') }}</span>
                        </div>
                    @endif
                    <x-modal
                        wire:click="stopSchedule"
                        type="cancel"
                        triggerClass="flex space-x-2 items-center text-xs border border-red-500 w-full text-left px-2 py-2 rounded-md text-red-500 hover:bg-red-100 hover:text-red-500"
                        modalTitle="Stop Schedule?"
                        submitText="Yes, Stop Schedule"
                    >
                        <x-slot name="triggerText">
                            <x-svg.cancel class="h-3 w-3"/>
                            <span>Stop Schedule</span>
                        </x-slot>
                        Are you sure you want to stop the schedule for this Email Template?<br><br>
                    </x-modal>
                </div>
                @if($email_template->send_date > now() && $email_template->set_send_date)
                    <div class="text-xs text-gray-600 font-medium flex">
                        Scheduled to send on {{ $email_template->send_date->format('M d, Y') }}
                        @ {{ config('clientportal.email-scheduler-send-time-human') }}
                    </div>
                @endif

            </div>
        </div>
    @endif
    <div class="mt-4">
        <button
            wire:click="showSentHistroy"
            class="relative flex space-x-2 py-1 px-1.5 @if($showSentHistory) bg-gray-50 rounded-t-md @else bg-gray-100 rounded-md @endif outline-none items-center text-xs text-gray-500 font-medium hover:bg-secondary-100 hover:text-secondary-500 s-transition"
        >
            <x-svg.email class="h-4 w-4"/>
            <span>View Sent History</span>
            <span class="rounded-full py-0.4 px-1 text-xs bg-secondary-500 text-white">
                {{ $email_template->emails->count() }}
            </span>
        </button>
        @if($showSentHistory)
            @livewire('admin.emails.sent-history', ['email_template' => $email_template], key('email-template-history-'.$email_template->id))
        @endif
    </div>
    <x-menu.three-dot :showMenuContent="$showMenuContent">
        @if($showMenuContent)
            <x-modal
                type="send"
                wire:click="sendEmail"
                triggerText="Send Email"
                triggerClass="block w-full text-left px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-100 hover:text-secondary-900"
                modalTitle="Send Email"
            >
                Are you sure you want to send this Email Template?<br><br>
                <div class="rounded-md p-3 bg-gray-100 mt-4">
                    <strong>Recipients:</strong>
                    <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                        @foreach($recipients as $recipient)
                            <li>{{ $recipient->full_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </x-modal>
            <x-modal
                wire:click="destroy"
                triggerText="Delete Email Template"
                triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                modalTitle="Delete Email Template"
            >
                Are you sure you want to delete this Email Template?<br><br>
            </x-modal>
        @else
            <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
        @endif
    </x-menu.three-dot>
</div>
