<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View Project's Client" route="{{ route('admin.clients.show', $project->client->id) }}">
            <x-svg.client class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($project->client->title,30) }}</span></x-bread-crumb.link>
        <x-bread-crumb.link :current="true">
            <x-svg.project class="h-5 w-5 text-gray-400"/>
            <span>{{ Str::limit($project->title,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:pb-6">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $project->title }}</h1>
                                    @if($project->due_date)
                                        <p class="mt-2 text-sm text-gray-500">
                                            Due on {{ date_format($project->due_date, 'M d, Y') }}
                                            | {{ $project->due_date->DiffForHumans() }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-4 flex space-x-3 md:mt-0">
                                    @livewire('admin.projects.edit', ['project' => $project, 'button_type' => 'edit'], key('project-edit'))
                                </div>
                            </div>
                        </div>
                    </div>
                    <x-tabs.container>
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="details"
                            tabSvg="info-circle"
                            tabLabel="Details"
                        />
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="resources"
                            tabSvg="resource"
                            tabLabel="Resources"
                        />
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="tasks"
                            tabSvg="task"
                            tabLabel="Tasks"
                        />
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="files"
                            tabSvg="file"
                            tabLabel="Files"
                        />
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="questions"
                            tabSvg="question"
                            tabLabel="Questions"
                        />
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="tutorials"
                            tabSvg="tutorial"
                            tabLabel="Tutorials"
                        />
                    </x-tabs.container>

                    @if($tab == 'details')
                        @if($project->description || $project->urls->count() > 0)
                            <section class="mt-8 xl:mt-10">
                                <div tooltip="About the Project" class="bg-white rounded shadow-md p-5 flex">
                                    <x-svg.info-circle class="-ml-1 mr-2 h-5 w-5 text-gray-400 inline"/>
                                    <div>
                                        @if($project->description)
                                            <p class="text-gray-500 text-sm">{!! $project->description !!}</p>
                                        @endif
                                        @if($project->urls->count() > 0)
                                            <div class="inline-flex mt-4 space-x-4 justify-start">
                                                @foreach($project->urls as $url)
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

                        @livewire('admin.phases.index-card', ['project' => $project, 'cardOpened' => true])
                    @endif
                    @if($tab == 'resources')
                        @livewire('admin.resources.index-card', ['model' => $project, 'cardOpened' => true], key('resource-index-card'))
                    @endif
                    @if($tab == 'tasks')
                        @livewire('admin.tasks.index-card', ['client' => $project->client, 'project' => $project, 'setClient' => false, 'setProject' => false, 'cardOpened' => true])
                    @endif
                    @if($tab == 'files')
                        @livewire('admin.files.index-card', ['model' => $project, 'allowUpload' => false, 'cardOpened' => true], key('file-index-card'))
                    @endif
                    @if($tab == 'questions')
                        @livewire('admin.questions.index-card', [
                        'client' => $project->client,
                        'project' => $project,
                        'setClient' => false,
                        'setService' => false,
                        'setPackage' => false,
                        'setProject' => false,
                        'setOnboarding' => false,
                        'assign_type' => 'project',
                        'cardOpened' => true
                        ])
                    @endif
                    @if($tab == 'tutorials')
                        @livewire('admin.tutorials.index-card', [
                        'client' => $project->client,
                        'project' => $project,
                        'setClient' => false,
                        'setService' => false,
                        'setPackage' => false,
                        'assign_type' => 'project',
                        'cardOpened' => true
                        ])
                    @endif
                    @livewire('admin.activities.index-card', ['model' => $project, 'cardOpened' => true], key('activity-index-card'))
                </div>
                @include('admin.projects.project-sidebar', ['project' => $project])
            </div>
        </div>
    </main>
</div>
