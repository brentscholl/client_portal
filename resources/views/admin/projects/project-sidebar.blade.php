<aside class="hidden xl:block xl:pl-8">
    <div class="flex space-x-2 items-center">
        @switch($project->status)
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
        @livewire('admin.status-changer', ['model' => $project, 'large' => true])
                <button
                    type="button"
                    wire:click="toggleVisibility"
                    class="rounded-full h-5 w-5 p-1 flex justify-center items-center {{ $project->visible ? 'text-green-400 bg-green-100 hover:bg-red-100 hover:text-red-400' : 'bg-red-100 text-red-400 hover:text-green-400 hover:bg-green-100' }} transition-all duration-100 ease-in-out"

                    tooltip-p="left"
                    @if($project->visible)
                    tooltip="Project is visible to Client. Click to toggle"
                    @else
                    tooltip="Project is hidden from Client. Click to toggle"
                    @endif
                >
                    @if($project->visible)
                        <x-svg.eye class="h-4 w-4"/>
                    @else
                        <x-svg.eye-off class="h-4 w-4"/>
                    @endif
                </button>
    </div>
    <h2 class="sr-only">Details</h2>
    <div class="space-y-5">
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            @if($project->archived)
                <svg tooltip="Project is archived" tooltip-p="left" class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <span class="text-green-700 text-sm font-medium">Archived</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd"
                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"/>
            </svg>
            @if($project->tasks)
                <span class="text-gray-900 text-sm font-medium">{{ $project->tasks ? $project->tasks->where('status', 'completed')->count() : '0' }}/{{ $project->tasks ? $project->tasks->count() : '0' }} Tasks completed</span>
            @else
                <span class="text-gray-900 text-sm font-medium">No tasks created</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div tooltip="Project Service" tooltip-p="left" class="flex items-center space-x-2">
            <x-dynamic-component :component="'svg.service.'.$project->service->slug" class="h-5 w-5 text-gray-400" />
            <span class="text-gray-900 text-sm font-medium">{{ $project->service->title }}</span>
        </div>

        <!-- Sidebar Item -->
        @if($project->packages->count() > 0)
            <div tooltip="Project Packages" tooltip-p="left" class="flex items-start space-x-2">
                <x-svg.package class="h-5 w-5 text-gray-400" />
                <span class="text-gray-900 text-sm font-medium">Packages:</span>
            </div>
            <ul class="ml-5">
                @foreach($project->packages as $package)
                    <li>
                        <a tooltip="View Package" tooltip-p="left" href="{{ route('admin.packages.show', $package->id) }}"
                            class="rounded-full bg-gray-200 py-0.5 px-2 text-gray-900 text-sm font-medium hover:bg-secondary-100 hover:text-secondary-500">
                            {{ $package->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
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
            <span class="text-gray-900 text-sm font-medium">Created on <time datetime="2020-12-02">{{ date_format($project->created_at, 'M d, Y @ h:ia') }}</time></span>
        </div>

        <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Project"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Project</span>
            </x-slot>
            Are you sure you want to delete this Project?<br>
            <div class="rounded-md p-3 bg-gray-100 mt-4">
                <strong>What will also be deleted:</strong>
                <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                    <li>Phases</li>
                    <li>Tasks (including Task Files)</li>
                    <li>Questions created specifically for this project (including Answers, and files)</li>
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
                    <a tooltip="View Project's Client" tooltip-p="left" href="{{ route('admin.clients.show', $project->client->id) }}" class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full bg-gray-800"
                                src="{{ $project->client->avatarUrl() }}"
                                alt="">
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $project->client->title }}</div>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div>
            @livewire('admin.assign-user', ['model' => $project, 'assign_marketing_advisor' => true])
        </div>
        <div>
            @livewire('admin.assign-user', ['model' => $project, 'allow_assign' => false])
        </div>

    </div>
</aside>
