<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View all Services" route="{{ route('admin.services.index') }}">Services</x-bread-crumb.link>
        <x-bread-crumb.link :current="true">
            <x-svg.service class="h-5 w-5 text-gray-400"/>
            <span>{{ $service->title }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <x-dynamic-component :component="'svg.service.'.$service->slug" class="w-10 h-10 text-gray-400"/>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $service->title }}</h1>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center space-x-3 md:mt-0">
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
                            tabLink="clients"
                            tabSvg="client"
                            tabLabel="Clients"
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
                    </x-tabs.container>

                    @if($tab == 'details')
                        @livewire('admin.packages.index-card', ['service' => $service, 'showService' => false, 'cardOpened' => true], key('package-index-card'))
                    @endif
                    @if($tab == 'clients')
                        @livewire('admin.clients.index-card', ['clients' => $service->clients, 'model' => $service, 'cardOpened' => true], key('client-index-card'))
                    @endif
                    @if($tab == 'projects')
                        @livewire('admin.projects.index-card', [
                            'projects' => $service->projects,
                            'service' => $service,
                            'setService' => false,
                            'showService' => false,
                            'cardOpened' => true
                            ], key('project-index-card'))
                    @endif
                    @if($tab == 'questions')
                        @livewire('admin.questions.index-card', ['service' => $service, 'setService' => true, 'setPackage' => true, 'setClient' => false, 'setProject' => false, 'setOnboarding' => false, 'assign_type' => 'service', 'cardOpened' => true], key('question-index-card'))
                    @endif
                    @if($tab == 'tutorials')
                        @livewire('admin.tutorials.index-card', ['service' => $service, 'setService' => true, 'setPackage' => true, 'setClient' => false, 'assign_type' => 'service', 'cardOpened' => true], key('tutorial-index-card'))
                    @endif
                </div>
                @include('admin.services.service-sidebar', ['service' => $service])
            </div>
        </div>
    </main>
</div>
