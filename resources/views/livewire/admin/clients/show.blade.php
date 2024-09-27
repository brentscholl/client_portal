<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View all Clients" route="{{ route('admin.clients.index') }}">Clients</x-bread-crumb.link>
        <x-bread-crumb.link :current="true" route="{{ route('admin.clients.show', $client->id) }}">
            <x-svg.client class="h-5 w-5"/>
            <span>{{ Str::limit($client->title,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    @if($client->hasAvatar())
                                        <div>
                                            <img class="h-12 w-auto"
                                                src="{{$client->avatarUrl()}}"
                                                alt="{{ $client->title }}">
                                        </div>
                                    @endif
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $client->title }}</h1>
                                        @if($client->website_url)
                                            <p class="mt-2 text-sm text-gray-500">
                                                <a href="{{ $client->website_url }}"
                                                    target="_blank">{{ $client->website_url }}</a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3 md:mt-0">
                                    @livewire('admin.clients.edit', ['client' => $client], key('client-edit'))
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
                            tabLink="projects"
                            tabSvg="project"
                            tabLabel="Projects"
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
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="files"
                            tabSvg="file"
                            tabLabel="Files"
                        />
                        <x-tabs.tab-link
                            :tab="$tab"
                            tabLink="tasks"
                            tabSvg="task"
                            tabLabel="Tasks"
                        />
                    </x-tabs.container>
                    @if($tab == 'details')
                        @livewire('admin.clients.client-reps', ['client' => $client, 'cardOpened' => true], key('client-team'))
                        @livewire('admin.emails.index-card', ['client' => $client, 'cardOpened' => true], key('email-index'))
                    @endif
                    @if($tab == 'resources')
                        @livewire('admin.resources.index-card', ['model' => $client, 'cardOpened' => true], key('resource-index-card'))
                    @endif
                    @if($tab == 'projects')
                        @livewire('admin.projects.index-card', ['client' => $client, 'projects' => $client->projects, 'setClient' => false, 'showService' => true, 'cardOpened' => true], key('project-index-card'))
                    @endif
                    @if($tab == 'questions')
                    @livewire('admin.questions.index-card', ['client' => $client, 'setService' => false, 'setClient' => true, 'setPackage' => false, 'setOnboarding' => false, 'cardOpened' => true], key('question-index-card'))
                    @endif
                    @if($tab == 'tutorials')
                        @livewire('admin.tutorials.index-card', ['client' => $client, 'setService' => false, 'setClient' => true, 'setPackage' => false, 'cardOpened' => true], key('tutorial-index-card'))
                    @endif
                    @if($tab == 'files')
                        @livewire('admin.files.index-card', ['model' => $client, 'allowUpload' => false, 'cardOpened' => true], key('file-index-card'))
                    @endif
                    @if($tab == 'tasks')
                        @livewire('admin.tasks.index-card', ['client' => $client, 'tasks' => $client->tasks, 'setClient' => false, 'cardOpened' => true], key('task-index-card'))
                    @endif
                    @livewire('admin.activities.index-card', ['model' => $client, 'cardOpened' => true], key('activity-index-card'))
                </div>
                @include('admin.clients.client-sidebar', ['client' => $client])
            </div>
        </div>
    </main>
</div>

