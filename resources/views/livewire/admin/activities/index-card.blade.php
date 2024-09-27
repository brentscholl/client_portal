<section class="mt-8 xl:mt-10">
    <x-card :opened="$cardOpened">
        <x-card.header title="Activity" :doesNotHaveCount="true"></x-card.header>
        <x-card.body :cardOpened="$cardOpened">
            <div>
                @if($cardOpened)
                    <div class="pb-6 bg-white">
                        <div class="max-w-7xl mx-auto">
                            <div class="flex space-between space-x-2 items-center"
                            ">
                            <nav class="flex flex-grow-1 w-full space-x-4" aria-label="Tabs">
                                <button
                                    type="button"
                                    wire:click="updateStatus('all')"
                                    @if($status == 'all')
                                    class="bg-secondary-100 text-secondary-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                    @else
                                    class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                    @endif
                                >
                                    All
                                </button>
                                <button
                                    type="button"
                                    wire:click="updateStatus('comments')"
                                    @if($status == 'comments')
                                    class="bg-secondary-100 text-secondary-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                    @else
                                    class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                    @endif
                                >
                                    Comments Only
                                </button>

                                <button
                                    type="button"
                                    wire:click="updateStatus('actions')"
                                    @if($status == 'actions')
                                    class="bg-secondary-100 text-secondary-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                    @else
                                    class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                    @endif
                                >
                                    Actions Only
                                </button>
                            </nav>
                            @if($model)
                                <div>
                                    <div class="hidden ml-6 bg-gray-100 p-0.5 rounded-lg items-center sm:flex">
                                        <button tooltip="See actions for {{ str_replace('App\\Models\\', '', get_class($model)) }} and sub items" wire:click="$set('filter_children', true)" type="button" class="@if($filter_children == true) bg-white shadow-sm text-amber-500 @else text-gray-400 hover:bg-white hover:shadow-sm @endif p-1.5 rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500">
                                            <x-svg.sitemap class="h-4 w-4"/>
                                        </button>
                                        <button tooltip="See actions for {{ str_replace('App\\Models\\', '', get_class($model)) }} only" wire:click="$set('filter_children', false)" type="button" class="@if($filter_children == false) bg-white shadow-sm text-secondary-500 @else text-gray-400 hover:bg-white hover:shadow-sm @endif ml-0.5 p-2.5 rounded-md text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500">
                                            <x-svg.square class="h-2 w-2"/>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
            </div>
            @if($actions)
                <div class="pt-6">
                    @if($actions->hasMorePages())
                        <div class="max-w-2xl mx-auto px-4 mb-4">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <button wire:click="loadMore()"
                                        type="button"
                                        class="inline-flex items-center shadow-sm px-2 py-0.5 border border-gray-200 text-xs font-medium rounded-full text-gray-400 bg-white hover:bg-secondary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                    >
                                        <svg class="-ml-1.5 mr-1 h-3 w-3 text-gray-400" x-description="Heroicon name: solid/plus-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Show More</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                @endif
                <!-- Activity feed-->
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @foreach($actions->reverse() as $action)
                                <li>
                                    <div class="relative {{ $action->type == 'comment' ? 'pb-4' : 'pb-8' }}">

                                        @if(!$loop->last)
                                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif

                                        <div class="group flex space-between space-x-2 rounded-md {{ $action->type != 'comment' ? 'hover:bg-gray-50 pr-3' : '' }}">
                                            <div class="flex-grow-1 w-full"
                                        @switch($action->type)
                                            @case('comment')
                                            <!-- Comment -->
                                            @livewire('admin.activities.comment', [
                                                'action' => $action,
                                                'mentionableUsers' => $mentionableUsers,
                                                'model' => $model,
                                                'show_reply' => $model ? ($action->actionable_type == get_class($model) && $action->actionable_id == $model->id ? false : true ) : true
                                                ], key('comment-'.$action->id))
                                            @break

                                            @case('status_update')
                                            <!-- Status Update -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            @switch($action->value)
                                                                @case('pending')
                                                                <span class="relative w-8 h-8 rounded-full ring-8 ring-white flex items-center justify-center flex items-center text-center text-gray-300 bg-gray-100">
                                                                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                        </svg>
                                                                                    </span>
                                                                @break
                                                                @case('in-progress')
                                                                <span class="relative w-8 h-8 rounded-full ring-8 ring-white flex items-center justify-center flex items-center text-center text-secondary-300 bg-secondary-100">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                        </svg>
                                                                                    </span>
                                                                @break
                                                                @case('completed')
                                                                <span class="relative w-8 h-8 rounded-full ring-8 ring-white flex items-center justify-center flex items-center text-center text-green-300 bg-green-100">
                                                                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                        </svg>
                                                                                    </span>
                                                                @break
                                                                @case('on-hold')
                                                                <span class="relative w-8 h-8 rounded-full ring-8 ring-white flex items-center justify-center flex items-center text-center text-amber-300 bg-amber-100">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                        </svg>
                                                                                    </span>
                                                                @break
                                                                @case('canceled')
                                                                <span class="relative w-8 h-8 rounded-full ring-8 ring-white flex items-center justify-center flex items-center text-center text-red-300 bg-red-100">
                                                                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                                                        </svg>
                                                                                    </span>
                                                                @break
                                                            @endswitch
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            changed status to
                                                            <!-- space -->
                                                            @switch($action->value)
                                                                @case('pending')
                                                                <span class="text-xs px-2 py-0.5 flex-shrink-0 inline-block focus:outline-none text-gray-800 font-medium bg-gray-200 rounded-full">
                                                                                        Pending
                                                                                    </span>
                                                                @break
                                                                @case('in-progress')
                                                                <span class="text-xs px-2 py-0.5 flex-shrink-0 inline-block focus:outline-none text-secondary-800 font-medium bg-secondary-100 rounded-full">
                                                                                        In Progress
                                                                                    </span>
                                                                @break
                                                                @case('completed')
                                                                <span class="text-xs px-2 py-0.5 flex-shrink-0 inline-block focus:outline-none text-green-800 font-medium bg-green-100 rounded-full">
                                                                                        Completed
                                                                                    </span>
                                                                @break
                                                                @case('on-hold')
                                                                <span class="text-xs px-2 py-0.5 flex-shrink-0 inline-block focus:outline-none text-amber-800 font-medium bg-amber-100 rounded-full">
                                                                                        On Hold
                                                                                    </span>
                                                                @break
                                                                @case('canceled')
                                                                <span class="text-xs px-2 py-0.5 flex-shrink-0 inline-block focus:outline-none text-red-800 font-medium bg-red-100 rounded-full">
                                                                                        Canceled
                                                                                    </span>
                                                                @break
                                                            @endswitch

                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('file_upload')
                                            <!-- File upload -->
                                                <div class="relative flex items-start space-x-3">

                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.files class="h-5 w-5 text-gray-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            uploaded
                                                            <strong>{{ $action->value }} {{ Str::plural('file', $action->value) }}</strong>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('resource_updated')
                                            <!-- Resource Created -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.resource class="h-5 w-5 text-gray-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            updated <strong>Resources</strong>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('model_updated')
                                            <!-- Model Updated -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.edit class="h-5 w-5 text-gray-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            updated the
                                                            <strong>{{ $action->value }}</strong> on
                                                            <!-- space -->
                                                            the
                                                            <!-- space -->
                                                        {{ str_replace('App\Models\\', '', $action->actionable_type) }}
                                                        <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('model_created')
                                            <!-- Model Created -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-secondary-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.plus-circle class="h-5 w-5 text-secondary-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            added a
                                                            <a class="font-medium text-gray-900 hover:text-secondary-500" href="{{ route('admin.' . $action->value . 's.show', $action->relation_id) }}">{{ Str::ucfirst($action->value) }}</a>
                                                            <!-- space -->
                                                            to
                                                            the {{ str_replace('App\Models\\', '', $action->actionable_type) }}
                                                        <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('email_template_created')
                                            <!-- Model Created -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-secondary-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.email class="h-5 w-5 text-secondary-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            @switch($action->value)
                                                                @case('sent')
                                                                sent an Email Template:
                                                                <strong>{{ $action->body }}</strong> to
                                                                @break
                                                                @case('scheduled')
                                                                scheduled an Email Template:
                                                                <strong>{{ $action->body }}</strong> to
                                                                @break
                                                                @case('scheduled_sent')
                                                                scheduled and sent an Email Template:
                                                                <strong>{{ $action->body }}</strong> to
                                                            @break
                                                        @endswitch
                                                        <!-- space -->
                                                            the
                                                            <a class="font-medium text-gray-900 hover:text-secondary-500" href="{{ route('admin.clients.show', $action->relation_id) }}">Client</a>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('email_template_updated')
                                            <!-- Model Created -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-secondary-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.email class="h-5 w-5 text-secondary-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            updated <strong>{{ $action->body }}</strong> email template
                                                            for the
                                                            <a class="font-medium text-gray-900 hover:text-secondary-500" href="{{ route('admin.clients.show', $action->client_id) }}">Client</a>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('user_assigned')
                                            <!-- User Assigned -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <svg class="h-5 w-5 text-gray-500" x-description="Heroicon name: solid/user-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            assigned
                                                            <!-- space -->
                                                            <a href="{{ route('admin.users.show', $action->relation_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ json_decode($action->data)->full_name }}</a>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('service_assigned')
                                            <!-- Service Assigned -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <x-svg.service class="h-5 w-5 text-gray-500"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            assigned
                                                            <!-- space -->
                                                            <a href="{{ route('admin.services.show', $action->relation_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->value }}</a>
                                                            Service
                                                            <!-- space -->
                                                            to the
                                                            <a href="{{ route('admin.clients.show', $action->actionable_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">Client</a>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('phase_reordered')
                                            <!-- Phase Reordered -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            reordered
                                                            <a href="{{ route('admin.projects.show', $action->actionable_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">Project</a>
                                                            Phases
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @case('primary_contact_set')
                                            <!-- Primary Contact Assigned -->
                                                <div class="relative flex items-start space-x-3">
                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <svg class="h-5 w-5 text-gray-500" x-description="Heroicon name: solid/user-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            assigned
                                                            <!-- space -->
                                                            <a href="{{ route('admin.users.show', $action->relation_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ json_decode($action->data)->full_name }}</a>
                                                            <!-- space -->
                                                            as the primary contact
                                                            to the
                                                            <a href="{{ route('admin.clients.show', $action->actionable_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">Client</a>
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break

                                            @default
                                            <!-- Action -->
                                                <div class="relative flex items-start space-x-3">

                                                    <div>
                                                        <div class="relative px-1">
                                                            <div class="h-8 w-8 bg-gray-100 rounded-full ring-8 ring-white flex items-center justify-center">
                                                                <svg class="h-5 w-5 text-gray-500" x-description="Heroicon name: solid/user-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1 py-1.5">
                                                        <div class="text-sm text-gray-500">
                                                            <a href="{{ route('admin.users.show', $action->user_id) }}" class="font-medium text-gray-900 hover:text-secondary-500">{{ $action->user->full_name }}</a>
                                                            <!-- space -->
                                                            made an update
                                                            <!-- space -->
                                                            <span class="text-xs whitespace-nowrap">{{ $action->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                            @endswitch
                                            @if($model ? (get_class($model) != $action->actionable_type && $model->id != $action->actionable_id) : false)
                                                <div class="block absolute top-9 left-2.5 rounded-full p-1 bg-white">
                                                    <x-svg.sitemap class="w-3 h-3 text-amber-400"/>
                                                </div>
                                            @endif
                                        </div>

                                        @if($action->user_id == auth()->user()->id && $action->type !== 'comment')
                                            <button
                                                type="button"
                                                wire:click="destroyAction({{ $action->id }})"
                                                class="opacity-0 group-hover:opacity-100 mr-2 text-red-300 hover:text-red-500 transition-all ease-in-out duration-100"
                                            >
                                                <x-svg.trash class="h-4 w-4"/>
                                            </button>
                                        @endif
                                    </div>

                                    @if($model ? (get_class($model) != 'App\Models\Service') : true)
                                        @if($model ? (get_class($model) != $action->actionable_type && $model->id != $action->actionable_id) : true)
                                            <div class="flex flex-col space-y-1 ml-12 relative overflow-hidden">
                                                <div class="w-1 h-full border-l-2 absolute left-0" style="top: -0.75rem;"></div>
                                                <div class="space-x-1 flex">
                                                    <div class="border-l-2 border-b-2 h-3 w-3"><!--space--></div>
                                                    <a href="{{ route('admin.'.  strtolower(str_replace('App\Models\\', '', $action->actionable_type)) .'s.show', $action->actionable_id) }}"
                                                        class="py-0.5 px-2 text-xs rounded-full bg-gray-100 text-gray-500 hover:bg-secondary-500 hover:text-white transition-all durration-200 ease-in-out">
                                                        <span class="font-medium">{{ str_replace('App\Models\\', '', $action->actionable_type) }}</span>
                                                        : {{ $action->actionable_type != 'App\Models\EmailTemplate' ? $action->actionable->title : $action->actionable->subject }}
                                                    </a>
                                                </div>
                                                @if($action->client && $showClientLabel)
                                                    <div class="space-x-1 flex">
                                                        <div class="border-l-2 border-b-2 h-3 w-3"><!--space--></div>
                                                        <a href="{{ route('admin.clients.show', $action->client_id) }}"
                                                            class="flex space-x-2 py-0.5 px-2 text-xs rounded-full bg-amber-100 text-amber-500 hover:bg-amber-500 hover:text-white transition-all durration-200 ease-in-out">
                                                            <x-svg.client class="h-4 w-4"/>
                                                            <span>{{ $action->client->title }}</span>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                </div>
            @else
                <span class="relative block w-full border-2 border-gray-200 border-dashed rounded-lg p-12 text-center">
                            <x-svg.activity class="mx-auto h-12 w-12 text-gray-200"/>
                            <span class="mt-2 block text-sm font-medium text-gray-300">
                              No Activity
                            </span>
                        </span>
            @endif
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
                @endif
                </div>

                @if($allowComments)
                <!-- Comment Create -->
                    <div class="bg-gray-100 px-4 py-6 mt-6 rounded-md">
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->full_name }}">
                            </div>
                            <div class="min-w-0 flex-1">
                                <form wire:submit.prevent="createComment">
                                    <div>
                                        <div>
                                            <div class="form-input-container">
                                                <div wire:ignore class="w-full">
                                            <textarea rows="3"
                                                id="comment"
                                                wire:model.defer="newComment"
                                                placeholder="Leave a comment"
                                                @error('newComment')
                                                class="form-input-container__input form-input-container__input--has-error"
                                                aria-invalid="true"
                                                aria-describedby="newComment-error"
                                                @else
                                                class="form-input-container__input"
                                                @endif
                                            ></textarea>
                                                </div>
                                                @error('newComment')
                                                <div wire:key="error_svg_newComment"
                                                    class="form-input-container__input__icon--has-error">
                                                    <x-svg.error/>
                                                </div>
                                                @enderror
                                            </div>
                                            @error('newComment')
                                            <p wire:key="error_newComment"
                                                class="form-input-container__input__error-message"
                                                id="error-newComment">
                                                {{ $message }}
                                            </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <x-button
                                            type="submit"
                                            btn="secondary"
                                        >
                                            Comment
                                        </x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
        </x-card.body>
    </x-card>
</section>
@once
@push('styles')
    @if($allowComments)
        <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css"/>
    @endif
@endpush
@endonce
@once
@push('scripts')
    @if($allowComments)
        <script type="text/javascript" src="{{ asset('js/tribute.js') }}"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/plugins/char_counter.min.js"></script>
        <script>

            var tribute = new Tribute({
                values: [
                        @foreach($mentionableUsers as $user)
                    {
                        key: '{{ $user->full_name }}', value: '{{ $user->id }}'
                    },
                    @endforeach
                ],
                selectTemplate: function (item) {
                    return '<a tooltip="View Mentioned User" href="/admin/users/' + item.original.value + '" data-userid="' + item.original.value + '" class="font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100">@' + item.original.key + '</a>';
                },
                // class added in the flyout menu for active item
                selectClass: 'mention-highlight',

                // class added to the menu container
                containerClass: 'mention-container',

                // class added to each list item
                itemClass: 'mention-item',
                // specify the minimum number of characters that must be typed before menu appears
                menuShowMinLength: 1
            });

            function initEditor() {
                var editor = new FroalaEditor('#comment', {
                    editorClass: 'w-full rounded-lg border',
                    charCounterCount: true,
                    charCounterMax: 600,
                    heightMin: 100,
                    heightMax: 200,
                    quickInsertTags: null,
                    toolbarButtons: [
                        ['bold', 'italic', 'underline', 'strikeThrough'],
                        ['formatOL', 'formatUL'],
                        ['insertLink', 'emoticons'],
                        ['undo', 'redo']
                    ],
                    events: {
                        initialized: function () {
                            var editor = this;

                            tribute.attach(editor.el);

                            editor.events.on('keydown', function (e) {
                                if (e.which == FroalaEditor.KEYCODE.ENTER && tribute.isActive) {
                                    return false;
                                }
                            }, true);
                        },
                        contentChanged: function () {
                            @this.
                            set('newComment', this.html.get());
                        }
                    }
                });
                return editor;
            }

            var editor = initEditor();
            Livewire.on('commentCreated', $event => {
                editor.destroy();
                document.getElementById("comment").value = "";
                editor = initEditor();
            });
        </script>
    @endif
@endpush
@endonce
