<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View Task's Client" route="{{ route('admin.clients.show', $task->client_id) }}">
            <x-svg.client class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($task->client->title,30) }}</span></x-bread-crumb.link>
        <x-bread-crumb.link tooltip="View Task's Project" route="{{ route('admin.projects.show', $task->project_id) }}">
            <x-svg.project class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($task->project->title,30) }}</span></x-bread-crumb.link>
        <x-bread-crumb.link tooltip="View Task's Phase" route="{{ route('admin.phases.show', $task->phase_id) }}">
            <x-svg.phase class="h-5 w-5 text-gray-400"/>
            <span>Phase: {{ Str::limit($task->phase->title,30) }}</span></x-bread-crumb.link>
        <x-bread-crumb.link :current="true">
            <x-svg.task class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($task->title,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    @if($task->priority == 2)
                                        <div class="flex items-center space-x-2">
                                            <span class="flex-shrink-0 inline-block px-4 py-1 shadow-sm focus:outline-none text-secondary-700 text-sm font-medium bg-secondary-100 rounded-full">
                                                High Priority
                                            </span>
                                        </div>
                                    @endif
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h1>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center space-x-3 md:mt-0">
                                    @livewire('admin.tasks.edit', ['task' => $task, 'button_type' => 'edit', 'setClient' => false, 'setProject' => false], key('task-edit'))
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($task->description || $task->urls->count() > 0)
                        <section class="mt-8 xl:mt-10">
                            <div tooltip="About the Task" class="bg-white rounded shadow-md p-5 flex">
                                <x-svg.info-circle class="-ml-1 mr-2 h-5 w-5 text-gray-400 inline"/>
                                <div>
                                    @if($task->description)
                                        <p class="text-gray-500 text-sm">{!! $task->description !!}</p>
                                    @endif
                                    @if($task->urls->count() > 0)
                                        <div class="inline-flex mt-4 space-x-4 justify-start">
                                            @foreach($task->urls as $url)
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
                    @livewire('admin.files.index-card', ['model' => $task, 'setClient' => false, 'client_id' => $task->client_id, 'cardOpened' => true], key('file-index-card'))
                    @livewire('admin.activities.index-card', ['model' => $task, 'cardOpened' => true], key('activity-index-card'))
                </div>
                @include('admin.tasks.task-sidebar', ['task' => $task])
            </div>
        </div>
    </main>
</div>
