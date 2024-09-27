<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View Phase's Client" route="{{ route('admin.clients.show', $phase->project->client->id) }}">
            <x-svg.client class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($phase->project->client->title,30) }}</span></x-bread-crumb.link>
        <x-bread-crumb.link tooltip="View Phase's Project" route="{{ route('admin.projects.show', $phase->project_id) }}">
            <x-svg.project class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($phase->project->title,30) }}</span></x-bread-crumb.link>
        <x-bread-crumb.link :current="true">
            <x-svg.phase class="h-5 w-5 text-gray-400"/>
            <span>Phase {{ $phase->step }}: {{ Str::limit($phase->title,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">Phase {{ $phase->step }}
                                            : {{ $phase->title }}</h1>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center space-x-3 md:mt-0">
                                    @livewire('admin.phases.edit', ['phase' => $phase, 'button_type' => 'edit'], key('phase-edit'))
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2">
                            <?php
                            $task_completed_count = $phase->tasks->where('status', 'completed')->count();
                            $task_in_progress_count = $phase->tasks->where('status', 'in-progress')->count();
                            $task_count = $phase->tasks->where('status', '!=', 'canceled')->count();
                            $complete_percent = $task_count > 0 ? $task_completed_count / $task_count * 100 : 1.5;
                            $in_progress_percent = $task_count > 0 ? ($task_in_progress_count + $task_completed_count) / $task_count * 100 : 1.5;
                            ?>
                            @if($task_count > 0)
                                <div class="inline-flex justify-between items-center w-full">
                                    <p class="text-xs">Tasks Completed
                                        <span class="ml-2 text-secondary-500 text-sm font-semibold">{{ $task_completed_count }} <span class="text-xs text-primary-200">/ {{ $task_count }}</span></span>
                                    </p>
                                </div>
                                <div class="bg-gray-200 shadow-inner rounded-full h-4 w-full mt-4 relative">
                                    <div tooltip="{{ $task_in_progress_count }} {{ Str::plural('Task', $task_in_progress_count) }} In Progress" tooltip-p="right" class="absolute left-0 top-0 h-4 bg-secondary-500 opacity-50 rounded-full" style="width: {{ $in_progress_percent - 2 }}%;"></div>
                                    <div tooltip="{{ $task_completed_count }} {{ Str::plural('Task', $task_completed_count) }} Completed" tooltip-p="right" class="absolute left-0 top-0 h-4 bg-secondary-500 rounded-full" style="width: {{ $complete_percent }}%;"></div>
                                </div>
                            @else
                                <p class="text-xs text-gray-400 mt-6 mb-4">This phase does not have any tasks.</p>
                            @endif
                        </div>
                    </div>
                    @if($phase->description || $phase->urls->count() > 0)
                        <section class="mt-8 xl:mt-10">
                            <div tooltip="About the Phase"  class="bg-white rounded shadow-md p-5 flex">
                                <x-svg.info-circle class="-ml-1 mr-2 h-5 w-5 text-gray-400 inline"/>
                                <div>
                                    @if($phase->description)
                                        <p class="text-gray-500 text-sm">{!! $phase->description !!}</p>
                                    @endif
                                    @if($phase->urls->count() > 0)
                                        <div class="inline-flex mt-4 space-x-4 justify-start">
                                            @foreach($phase->urls as $url)
                                                <a tooltip="Open URL in new tab" href="{{ $url->url }}" target="_blank" class="flex shadow-sm rounded-md">
                                                    <div class="flex-shrink-0 flex items-center justify-center w-16 bg-gray-100 text-gray-500 text-sm font-medium rounded-l-md">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                                        <div class="flex-1 px-6 py-2 text-sm truncate">
                                                            <span class="text-gray-900 font-medium hover:text-gray-600">{{ $url->label }}</span>
                                                            <p class="text-xs text-gray-300">{{ Str::limit($url->url, 20)}}</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif

                    @livewire('admin.tasks.index-card', [
                        'phase' => $phase,
                        'client' =>  $phase->project->client,
                        'project' => $phase->project,
                        'setClient' => false,
                        'setProject' => false,
                        'setPhase' => false,
                        'cardOpened' => true
                        ])
                    @livewire('admin.activities.index-card', ['model' => $phase, 'cardOpened' => true], key('activity-index-card'))
                </div>
                @include('admin.phases.phase-sidebar', ['phase' => $phase])
            </div>
        </div>
    </main>
</div>
