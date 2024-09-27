<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View all Services" route="{{ route('admin.services.index') }}">Services</x-bread-crumb.link>
        <x-bread-crumb.link tooltip="View Package's Service" route="{{ route('admin.services.show', $package->service->id) }}">{{ $package->service->title }}</x-bread-crumb.link>
        <x-bread-crumb.link :current="true">{{ $package->title }}</x-bread-crumb.link>
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
                                        <x-dynamic-component :component="'svg.package'" class="w-10 h-10 text-gray-400"/>
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $package->title }}</h1>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center space-x-3 md:mt-0">
                                    @livewire('admin.packages.edit', ['package' => $package], key('package-edit'))
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
                            tabLink="projects"
                            tabSvg="project"
                            tabLabel="Projects"
                        />
                    </x-tabs.container>
                    @if($tab == 'details')
                        <section class="mt-8 xl:mt-10">
                            <div tooltip="About this Package" class="bg-white rounded shadow-md p-5 flex">
                                <x-svg.info-circle class="-ml-1 mr-2 h-5 w-5 text-gray-400 inline"/>
                                <div>
                                    <p class="text-gray-500 text-sm">{!! $package->description !!}</p>
                                </div>
                            </div>
                        </section>
                        <section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
                            <div class="flex flex-col space-y-3 min-w-0 border-l-2 pl-2 border-dashed border-gray-100">
                                <div class="flex-1 flex space-x-3 min-w-0">
                                    <x-svg.client class="h-10 w-10 text-gray-400"/>
                                    <p class="text-left text-xl font-bold text-gray-900 truncate inline-flex items-center">
                                        Clients with this package:
                                    </p>
                                </div>
                                <ul class="flex flex-wrap space-x-2">
                                    @forelse($clients as $client)
                                        <li class="mb-2">
                                            <a class="rounded-full bg-amber-200 text-amber-900 py-1 px-4 hover:bg-secondary-100 hover:text-secondary-500 transition-all duration-100 ease-in-out" href="{{ route('admin.clients.show', $client->id) }}">
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @empty
                                        <p class="text-xs text-center">No clients assigned to this package.</p>
                                    @endforelse
                                </ul>
                            </div>
                        </section>
                    @endif
                    @if($tab == 'questions')
                        @livewire('admin.questions.index-card', [
                            'package' => $package,
                            'service' => $package->service,
                            'setClient' => false,
                            'setProject' => false,
                            'setService' => false,
                            'setPackage' => false,
                            'setOnboarding' => false,
                            'assign_type' => 'package',
                            'cardOpened' => true,
                        ], key('question-index-card'))
                    @endif
                    @if($tab == 'tutorials')
                        @livewire('admin.tutorials.index-card', [
                            'package' => $package,
                            'service' => $package->service,
                            'setClient' => false,
                            'setService' => false,
                            'setPackage' => false,
                            'assign_type' => 'package',
                            'cardOpened' => true,
                        ], key('tutorial-index-card'))
                    @endif
                    @if($tab == 'projects')
                        @livewire('admin.projects.index-card', ['package' => $package, 'showService' => false, 'cardOpened' => true], key('project-index-card'))
                    @endif

                </div>
                @include('admin.services.package-sidebar', ['package' => $package])
            </div>
        </div>
    </main>
</div>
