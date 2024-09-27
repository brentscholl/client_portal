<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View all Tutorials" route="{{ route('admin.tutorials.index') }}">Tutorials</x-bread-crumb.link>
        <x-bread-crumb.link :current="true" route="{{ route('admin.tutorials.show', $tutorial->id) }}"><x-svg.tutorial class="h-5 w-5 text-gray-400" /><span>{{ Str::limit($tutorial->title,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    <x-svg.tutorial class="h-10 w-10 text-gray-400"/>
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $tutorial->title }}</h1>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3 md:mt-0">
                                    @livewire('admin.tutorials.edit', ['tutorial' => $tutorial, 'setClient' => true, 'setService' => true], key('tutorial-edit'))
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($tutorial->body)
                        <section class="mt-8 xl:mt-10">
                            <div tooltip="Tutorial Description" class="bg-white rounded shadow-md p-5 flex">
                                <x-svg.info-circle class="-ml-1 mr-2 h-5 w-5 text-gray-400 inline"/>
                                <div>
                                    <p class="text-gray-500 text-sm">{!! $tutorial->body !!}</p>
                                </div>
                            </div>
                        </section>
                    @endif

                    <section class="mt-8 xl:mt-10">
                        <div class="bg-white rounded shadow-md p-5 flex">
                            <div class="mx-auto h-auto w-full relative">
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6">
                                    <x-svg.spinner class="text-secondary-500 w-6 h-6"/>
                                </div>
                                <iframe
                                    style="position:relative; width: 100%; height: 100%; min-height: 24rem;"
                                    width="600"
                                    height="400"
                                    src="https://www.loom.com/embed/{{ $embed_id }}?hide_share=true&hideEmbedTopBar=true"
                                    title="{{ $tutorial->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen
                                >
                                </iframe>
                            </div>

                            <div style="position: relative; height: auto; min-height: 500px;">
                                <iframe src="https://www.loom.com/embed/eb6b38093e5f4fd396bf48292ad71c5b"
                                    frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; min-height: 500px;"></iframe>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div class="inline-flex mt-4 space-x-4 justify-start">
                            <a tooltip="View Tutorial on Loom" href="{{ $tutorial->video_url }}" target="_blank" class="flex shadow-sm rounded-md">
                                <div class="flex-shrink-0 flex items-center justify-center w-16 bg-gray-200 text-gray-500 text-sm font-medium rounded-l-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                    <div class="flex-1 px-6 py-2 text-sm truncate">
                                        <span class="text-gray-500 text-xs hover:text-gray-600">{{ $tutorial->video_url }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </section>

                    <section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
                        <div class="flex flex-col space-y-3 min-w-0 border-l-2 pl-2 border-dashed border-gray-100">
                            <div class="flex-1 flex space-x-3 min-w-0">
                                <x-svg.client class="h-10 w-10 text-gray-400"/>
                                <p class="text-left text-xl font-bold text-gray-900 truncate inline-flex items-center">
                                    Clients shown tutorial:
                                </p>
                            </div>
                            <ul class="flex flex-wrap space-x-2">
                                    @forelse($clients as $client)
                                        <li class="mb-2">
                                            <a tooltip="View Tutorial's Client" class="rounded-full bg-amber-200 text-amber-900 py-1 px-4 hover:bg-secondary-100 hover:text-secondary-500 transition-all duration-100 ease-in-out" href="{{ route('admin.clients.show', $client->id) }}">
                                                {{ $client->title }}
                                            </a>
                                        </li>
                                    @empty
                                        <p class="text-xs text-center">No clients assigned to this tutorial.</p>
                                    @endforelse
                            </ul>
                        </div>
                    </section>
                </div>
                @include('admin.tutorials.tutorial-sidebar', ['tutorial' => $tutorial])
            </div>
        </div>
    </main>
</div>


