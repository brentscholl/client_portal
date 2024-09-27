<div class="relative rounded-lg border border-gray-200 bg-white pr-6 pl-8 py-5 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.tutorial  class="h-4 w-4 text-gray-500"/>
    </div>

    <div class="flex flex-col space-y-3">

        <!---- Tutorial Top Container ---->
        <div class="flex space-x-2 justify-between">

            <div class="flex-1 flex-col space-y-3 min-w-0">
                <div class="flex space-x-2">
                    <a tooltip="View Tutorial" href="{{ route('admin.tutorials.show', $tutorial->id) }}">{{ $tutorial->title }}</a>
                </div>
            </div>

            <div class="flex flex-col space-y-3 min-w-0 border-l-2 pl-2 border-dashed border-gray-100">
                @if(!$play)
                    <button type="button"
                        wire:click="$set('play', true)"
                        class="group w-12 h-12 rounded-full inline-flex justify-center items-center bg-white border-2 border-secondary-500 text-secondary-500 hover:text-secondary-400 hover:bg-gray-100 transition duration-100 ease-in-out"
                        tooltip="Watch Tutorial"
                        tooltip-p="left"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                    </button>
                @else
                    <div class="mx-auto h-auto w-full relative">
                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-6 h-6">
                            <x-svg.spinner class="text-secondary-500 w-6 h-6"/>
                        </div>
                        <iframe
                            style="position:relative; width: 100%; height: 100%; min-height: 11rem;"
                            width="400"
                            height="200"
                            src="https://www.loom.com/embed/{{ $embed_id }}?hide_share=true&hideEmbedTopBar=true&autoplay=1"
                            title="{{ $tutorial->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen
                        >
                        </iframe>
                    </div>
                @endif
            </div>
        </div><!-- top container ends -->

        @if( $tutorial->services->count() > 0 )
            <div class="flex-1 flex flex-col space-y-3 min-w-0 border-t-2 pt-6 border-dashed border-gray-100">
                <div class="flex justify-between">
                    <div class="flex-1 flex items items-center space-x-3 min-w-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                            Service attached to:
                        </p>
                        <ul class="flex space-x-2">
                            @foreach($tutorial->services as $service)
                                <li>
                                    <a tooltip="View Service" href="{{ route('admin.services.show', $service->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                        <x-dynamic-component :component="'svg.service.'.$service->slug" class="h-4 w-4 text-secondary-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                                        <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $service->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if( $tutorial->packages->count() > 0 )
            <div class="flex items items-center space-x-3 min-w-0 border-t-2 pt-6 border-dashed border-gray-100">
                <x-svg.package class="h-5 w-5 text-gray-400" />
                <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                    Attached to&nbsp;<a tooltip="View Packages's Service" href="{{ route('admin.services.show', $tutorial->packages->first()->service->id) }}" class="font-bold hover:text-secondary-500">{{ $tutorial->packages->first()->service->title }}</a>&nbsp;Package:
                </p>
                <ul class="flex space-x-2">
                    <li>
                        <a tooltip="View Package" href="{{ route('admin.packages.show', $tutorial->packages->first()->id) }}" class=" group rounded-full bg-indigo-100 text-xs text-indigo-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-indigo-500 hover:text-white">
                            <x-dynamic-component :component="'svg.service.'.$tutorial->packages->first()->service->slug" class="h-4 w-4 text-indigo-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                            <span class="text-indigo-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $tutorial->packages->first()->title }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif


    <!---- Tutorial Client ---->
        @if( $tutorial->clients->count() > 0 )
            <div class="flex-1 flex flex-col space-y-3 min-w-0 border-t-2 pt-3 border-dashed border-gray-100">
                <div class="flex-1 flex items-center space-x-3 min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                        Unique tutorial for client:
                    </p>
                    <a tooltip="View Client" href="{{ route('admin.clients.show', $tutorial->clients->first()->id) }}" class="group rounded-full bg-amber-100 text-xs text-amber-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                        <span class="text-amber-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $tutorial->clients->first()->title }}</span>
                    </a>
                </div>
            </div>
        @endif

    </div>

    <!--- Menu ---->
    <x-menu.three-dot>
        <div>
            @if($showMenuContent)
                @livewire('admin.tutorials.edit', [
                    'tutorial' => $tutorial,
                    'button_type' => 'inline-menu',
                    'setService' =>  $setService,
                    'setClient' => $setClient,
                    'setPackage' => $setPackage
                    ], key('tutorial-edit-' . $tutorial->id))

                <x-modal
                    wire:click="destroy"
                    triggerText="Delete Tutorial"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete Tutorial"
                >
                    Are you sure you want to delete this Tutorial?<br><br>
                    <strong>{{ $tutorial->title }}</strong><br><br>
                    <small>
                        @if($tutorial->services->count() > 0)
                            Note: This tutorial will be deleted from the attached service and all clients.
                        @elseif($tutorial->packages->count() > 0)
                            Note: This tutorial will be deleted from the package and all clients.
                        @elseif($tutorial->clients->count() > 0)
                            Note: This tutorial will be deleted from all clients.
                        @endif
                    </small>
                </x-modal>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </div>
    </x-menu.three-dot>
</div>
