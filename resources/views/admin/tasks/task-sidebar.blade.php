<aside class="hidden xl:block xl:pl-8">
    <div class="flex space-x-2 items-center">
        @switch($task->status)
            @case('pending')
            <span class="relative w-6 h-6 flex items-center text-center text-gray-300">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            @break
            @case('in-progress')
            <span class="relative w-6 h-6 flex items-center text-center text-secondary-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            @break
            @case('completed')
            <span class="relative w-6 h-6 flex items-center text-center text-green-300">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            @break
            @case('on-hold')
            <span class="relative w-6 h-6 flex items-center text-center text-amber-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            @break
            @case('canceled')
            <span class="relative w-6 h-6 flex items-center text-center text-red-300">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </span>
            @break
        @endswitch
        @livewire('admin.status-changer', ['model' => $task, 'large' => true])
                <button
                    type="button"
                    wire:click="toggleVisibility"
                    class="rounded-full h-5 w-5 p-1 flex justify-center items-center {{ $task->visible ? 'text-green-400 bg-green-100 hover:bg-red-100 hover:text-red-400' : 'bg-red-100 text-red-400 hover:text-green-400 hover:bg-green-100' }} transition-all duration-100 ease-in-out"

                    tooltip-p="left"
                    @if($task->visible)
                    tooltip="Task is visible to Client. Click to toggle"
                    @else
                    tooltip="Task is hidden from Client. Click to toggle"
                    @endif
                >
                    @if($task->visible)
                        <x-svg.eye class="h-4 w-4"/>
                    @else
                        <x-svg.eye-off class="h-4 w-4"/>
                    @endif
                </button>
                <x-tooltip position="top">
                    @if($task->visible)
                        Client can see this task. Click to hide task
                    @else
                        Task is hidden from Client. Click to show task.
                    @endif
                </x-tooltip>
    </div>
    <div class="mt-6 border-t border-gray-200 pt-6 space-y-5">
        <!-- Sidebar Item -->
        <div tooltip="Task's Project" tooltip-p="left" class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path
                    d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"/>
            </svg>
            <span class="text-gray-900 text-sm font-medium hover:text-secondary-500"><a href="{{ route('admin.projects.show', $task->project_id) }}">Project: <strong>{{ $task->project->title }}</strong></a></span>
        </div>

        <!-- Sidebar Item -->
        <div tooltip="Task's Phase" tooltip-p="left" class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                <path d="M2 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4zM8 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1H9a1 1 0 01-1-1V4zM15 3a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V4a1 1 0 00-1-1h-2z"/>
            </svg>
            <span class="text-gray-900 text-sm font-medium hover:text-secondary-500"><a href="{{ route('admin.phases.show', $task->phase_id) }}">Phase: <strong>{{ $task->phase->title }}</strong></a></span>
        </div>

        <!-- Sidebar Item -->
        @switch($task->type)
            @case('detail')
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Type: <strong>Detail</strong></span>
            </div>
            @break
            @case('approval')
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Type: <strong>Approval</strong></span>
            </div>
            @break
        @endswitch

    <!-- Sidebar Item -->
        @if($task->status == 'completed')
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Completed on {{ $task->due_date->format('M d, Y') }}</span>
            </div>
        @elseif($task->status == 'in-progress' || $task->status == 'pending')
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                @if($task->due_date)
                    <span class="text-gray-900 text-sm font-medium">Due {{ $task->due_date->diffForHumans() }} | {{ $task->due_date->format('M d, Y') }}</span>
                @else
                    <span class="text-gray-900 text-sm font-medium">Due date not set.</span>
                @endif
            </div>
        @endif

        <!-- Sidebar Item -->
        @if($task->teams->count() > 0)
            <div class="flex items-center space-x-2">
                <x-svg.team class="h-5 w-5 text-gray-400"/>
                <span class="text-gray-900 text-sm font-medium">Assigned to {{ Str::plural('Team', $task->teams->count()) }}:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($task->teams as $team)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Task's Team" tooltip-p="left" href="{{ route('admin.teams.show', $team->id) }}" class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $team->title }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: calendar"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-900 text-sm font-medium">Created on <time datetime="2020-12-02">{{ date_format($task->created_at, 'M d, Y @ h:ia') }}</time></span>
        </div>

        <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Task"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Task</span>
            </x-slot>
            Are you sure you want to delete this task?<br>
            <div class="rounded-md p-3 bg-gray-100 mt-4">
                <strong>What will also be deleted:</strong>
                <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                    <li>Files</li>
                </ul>
            </div>
        </x-modal>
    </div>
    <div class="mt-6 border-t border-gray-200 py-6 space-y-8">
        <div>
            <h2 class="text-sm font-medium text-gray-500">Client</h2>
            <ul class="mt-3 space-y-3">
                <li class="flex justify-start">
                    <a tooltip="View Task's Client" tooltip-p="left" href="{{ route('admin.clients.show', $task->client->id) }}" class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full bg-gray-800"
                                src="{{ $task->client->avatarUrl() }}"
                                alt="">
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $task->client->title }}</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div>
            @livewire('admin.assign-user', ['model' => $task, 'assign_marketing_advisor' => true, 'allow_assign' => false ])
        </div>
    </div>
</aside>
