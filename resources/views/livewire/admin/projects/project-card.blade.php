<?php
    $borderColor = "border-gray-200";
    switch($project->status){
        case('in-progress'):
            $borderColor = 'border-secondary-300 pulse-border-secondary';
            break;
        case('completed'):
            $borderColor = 'border-green-300';
            break;
        case('on-hold'):
            $borderColor = 'border-amber-300';
            break;
        case('canceled'):
            $borderColor = 'border-red-300';
            break;
    }
?>
<div class="relative rounded-lg border {{ $borderColor }} bg-white pr-6 pl-8 py-5 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border {{ $borderColor }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.project  class="h-4 w-4 text-gray-500"/>
    </div>
    <div class="flex items-center space-x-3">
        <div class="flex-1 min-w-0">
            <p class="flex w-full text-xs text-left text-gray-500 truncate inline-flex items-center">
                @if($showService)
                    <span class="flex items-center">
                        <x-dynamic-component :component="'svg.service.'.$project->service->slug" class="h-6 w-6 mr-2 text-gray-300" />
                        <a tooltip="View Project's Service" href="{{ route('admin.services.show', $project->service->id) }}" class="hover:text-secondary-500">{{ $project->service->title }}</a>
                    </span>
                @else
                    <span class="flex items-center">
                        <svg class="h-6 w-6 mr-2 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <a tooltip="View Project's Client" href="{{ route('admin.clients.show', $project->client->id) }}" class="hover:text-secondary-500">{{ $project->client->title }}</a>
                    </span>
                @endif
                @if($project->packages->count() > 0)
                    <span tooltip="Packages" class="flex items-center ml-4 text-xs rounded-full py-0.5 px-2 bg-gray-100 text-gray-400">
                        <x-svg.package class="h-3 w-3 mr-2 text-gray-400" />{{ $project->packages()->count() }}
                    </span>
                @endif
            </p>
            <a tooltip="View Project" href="{{ route('admin.projects.show', $project->id) }}" class="focus:outline-none">
                <p class="inline text-lg font-medium text-gray-900 transition-all hover:text-secondary-500">
                    {{ $project->title }}
                </p>
            </a>
            @if($project->status == 'completed')
                <p class="text-xs text-left text-gray-500 truncate mt-2">Completed on {{ $project->completed_at->format('M d, Y') }}</p>
            @elseif($project->status == 'in-progress' || $project->status == 'pending')
                @if($project->due_date)
                    <p class="text-xs text-left text-gray-500 truncate mt-2">Due {{ $project->due_date->diffForHumans() }} | {{ $project->due_date->format('M d, Y') }}</p>
                @else
                    <p class="text-xs text-left text-gray-500 truncate mt-2">Due date not set.</p>
                @endif
            @endif
        </div>
        <div class="flex space-x-2 items-center">
            @livewire('admin.status-changer', ['model' => $project, 'class' => 'h-7'], key('project-status-changer-' . $project->id))
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
    </div>
    <div>
        <!-- This example requires Tailwind CSS v2.0+ -->
        <nav aria-label="Progress">
            <ol class="flex items-center mt-4">
                @foreach($project->phases as $phase)
                <li class="relative {{ $loop->last ? '' : 'pr-10' }}">
                    @switch($phase->status)
                        @case('completed')
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="h-0.5 w-full bg-secondary-500"></div>
                        </div>
                        <a tooltip="View Phase {{ $phase->step }} (Completed)" href="{{ route('admin.phases.show', $phase->id) }}" class="relative w-6 h-6 flex items-center justify-center bg-secondary-500 rounded-full hover:bg-secondary-600">
                            <!-- Heroicon name: solid/check -->
                            <svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        @break
                        @case('in-progress')
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="h-0.5 w-full bg-gray-200"></div>
                        </div>
                        <a tooltip="View Phase {{ $phase->step }} (In Progress)" href="{{ route('admin.phases.show', $phase->id) }}" class="group relative w-6 h-6 flex items-center justify-center bg-white border-2 border-secondary-500 rounded-full hover:border-secondary-600">
                            <span class="text-xs text-secondary-500">{{ $phase->step }}</span>
                        </a>
                        @break
                        @case('pending')
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="h-0.5 w-full bg-gray-200"></div>
                        </div>
                        <a tooltip="View Phase {{ $phase->step }} (Pending)" href="{{ route('admin.phases.show', $phase->id) }}" class="group relative w-6 h-6 flex items-center justify-center bg-white border-2 border-gray-200 rounded-full hover:border-gray-400">
                            <span class="text-xs text-gray-400">{{ $phase->step }}</span>
                        </a>
                        @break
                    @endswitch
                </li>
                @endforeach
            </ol>
        </nav>
    </div>
    <x-menu.three-dot :showMenuContent="$showMenuContent">
        @if($showMenuContent)
            @livewire('admin.projects.edit', ['project' => $project, 'button_type' => 'inline-menu'], key('project-edit-' . $project->id))
            <x-modal
                wire:click="destroy"
                triggerText="Delete Project"
                triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                modalTitle="Delete Project"
            >
                Are you sure you want to delete this Project?<br><br>
                <strong>{{ $project->title }}</strong>
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
        @else
            <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
        @endif
    </x-menu.three-dot>
</div>
