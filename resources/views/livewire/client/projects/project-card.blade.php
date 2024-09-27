<?php
    $borderColor = "border-gray-200";
    switch ($project->status) {
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
<div
    class="relative rounded-lg border {{ $borderColor }} bg-white pr-6 pl-8 py-5 shadow-sm max-w-7xl w-full">
    <!-- Card Icon -->
    <div
        class="rounded-full bg-white border {{ $borderColor }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center"
        style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.project class="h-4 w-4 text-gray-500"/>
    </div>
    <div class="flex items-center space-x-3">
        <div class="flex-1 min-w-0">
            <p class="flex w-full text-xs text-left text-gray-500 truncate inline-flex items-center">
                <span tooltip="View Project's Service" tooltip-p="left" class="flex items-center">
                    <x-dynamic-component :component="'svg.service.'.$project->service->slug"
                        class="h-6 w-6 mr-2 text-gray-300"/>
                    <a href="{{ route('client.services.show', $project->service->id) }}"
                        class="hover:text-secondary-500">{{ $project->service->title }}</a>
                </span>
            </p>
            <a tooltip="View Project" tooltip-p="left" href="{{ route('client.projects.show', $project->id) }}"
                class="focus:outline-none">
                <p class="inline text-lg font-medium text-gray-900 transition-all hover:text-secondary-500">
                    {{ $project->title }}
                </p>
            </a>
            @if($project->status == 'completed')
                <p class="text-xs text-left text-gray-500 truncate mt-2">Completed
                    on {{ $project->completed_at->format('M d, Y') }}</p>
            @elseif($project->status == 'in-progress' || $project->status == 'pending')
                @if($project->due_date)
                    <p class="text-xs text-left text-gray-500 truncate mt-2" tooltip="Project's due date" tooltip-p="left">
                        Due {{ $project->due_date->diffForHumans() }}
                        | {{ $project->due_date->format('M d, Y') }}</p>
                @else
                    <p class="text-xs text-left text-gray-500 truncate mt-2">Due date not set.</p>
                @endif
            @endif
        </div>
        <div class="flex flex-col space-y-3 items-end">
            <div tooltip="Project's status">
            @switch($project->status)
                @case('pending')
                    <span
                        class="text-sm px-4 py-1 shadow-sm flex-shrink-0 inline-block focus:outline-none text-gray-800 font-medium bg-gray-200 rounded-full">
                        Pending
                    </span>
                @break
                @case('in-progress')
                    <span
                        class="text-sm px-4 py-1 shadow-sm flex-shrink-0 inline-block focus:outline-none text-secondary-800 font-medium bg-secondary-100 rounded-full">
                        In Progress
                    </span>
                @break
                @case('completed')
                    <span
                        class="text-sm px-4 py-1 shadow-sm flex-shrink-0 inline-block focus:outline-none text-green-800 font-medium bg-green-100 rounded-full">
                        Completed
                    </span>
                @break
                @case('on-hold')
                    <span
                        class="text-sm px-4 py-1 shadow-sm flex-shrink-0 inline-block focus:outline-none text-amber-800 font-medium bg-amber-100 rounded-full">
                        On Hold
                    </span>
                @break
                @case('canceled')
                    <span
                        class="text-sm px-4 py-1 shadow-sm flex-shrink-0 inline-block focus:outline-none text-red-800 font-medium bg-red-100 rounded-full">
                        Canceled
                    </span>
                @break
            @endswitch
            </div>
            <div class="flex -space-x-2 relative z-0 overflow-hidden">
                @if($assignees->count() > 0)
                    @foreach($assignees as $assignee)
                        <img tooltip="{{ $assignee->user->full_name }} is assigned to this project." class="relative inline-block h-8 w-8 rounded-full ring-2 ring-white" src="{{ $assignee->user->avatarUrl() }}" alt="">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div>
        <div class="mx-auto max-w-7xl py-4">

            <nav aria-label="Progress">
                <ol role="list"
                    class="border border-gray-300 rounded-md divide-y divide-gray-300 md:flex md:divide-y-0">
                    @foreach($project->phases as $phase)
                        <li class="relative md:flex-1 md:flex">
                            <a href="{{ route('client.phases.show', $phase->id) }}"
                                tooltip="View Phase {{ $phase->step }}"
                                class="group flex items-center w-full">
                                @switch($phase->status)
                                    @case('completed')
                                        <span class="px-6 py-4 flex items-center text-sm font-medium">
                                            <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-green-600 rounded-full group-hover:bg-secondary-800">
                                                <svg class="w-6 h-6 text-white"
                                                    x-description="Heroicon name: solid/check"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor" aria-hidden="true">
                                                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                            <span class="ml-4 text-sm font-medium text-green-500">{{ $phase->title }}</span>
                                        </span>
                                    @break
                                    @case('in-progress')
                                        <span class="px-6 py-4 flex items-center text-sm font-medium">
                                            <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center pulse-border-secondary border-2 rounded-full">
                                                <span class="text-secondary-600">{{ $phase->step }}</span>
                                            </span>
                                            <span class="ml-4 text-sm font-medium text-secondary-500">{{ $phase->title }}</span>
                                        </span>
                                    @break
                                    @case('pending')
                                        <span class="px-6 py-4 flex items-center text-sm font-medium">
                                            <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full group-hover:border-gray-400">
                                              <span class="text-gray-500 group-hover:text-gray-900">{{ $phase->step }}</span>
                                            </span>
                                            <span class="ml-4 text-sm font-medium text-gray-500 group-hover:text-gray-900">{{ $phase->title }}</span>
                                        </span>
                                    @break
                                @endswitch
                            </a>

                        @if(! $loop->last)
                            <!-- Arrow separator for lg screens and up -->
                                <div class="hidden md:block absolute top-0 right-0 h-full w-5"
                                    aria-hidden="true">
                                    <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80"
                                        fill="none"
                                        preserveAspectRatio="none">
                                        <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke"
                                            stroke="currentcolor" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>
