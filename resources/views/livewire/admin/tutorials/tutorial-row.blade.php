<tr>
    <td>
        @if($tutorial->clients->count() > 0)
            <svg tooltip="Attached to Client" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        @elseif($tutorial->services->count() > 0)
            <svg tooltip="Attached to Service" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        @elseif($tutorial->packages->count() > 0)
            <span tooltip="Attached to Package"><x-svg.package class="h-5 w-5 text-gray-400"/></span>
        @endif
    </td>
    <td>
        <div class="leading-5 font-medium">
            <a tooltip="View Tutorial" class="text-gray-900 hover:text-secondary-500" href="{{ route('admin.tutorials.show', $tutorial->id) }}">{{ $tutorial->title }}</a>
        </div>
    </td>
    <td>
        <div class="leading-5 font-medium  flex space-x-2 items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            <a tooltip="See video in Loom" class="text-gray-900 text-xs hover:text-secondary-500" href="{{ $tutorial->video_url }}" target="_blank">{{ Str::limit($tutorial->video_url,50) }}</a>
        </div>
    </td>
    <td>
        @if($tutorial->clients->count() > 0)
            Client
        @elseif($tutorial->services->count() > 0)
            Services
        @elseif($tutorial->packages->count() > 0)
            Package
        @elseif($tutorial->projects->count() > 0)
            Project
        @endif
    </td>
    <td>
        @if($tutorial->clients->count() > 0)
            <ul class="flex space-x-2">
                @foreach($tutorial->clients as $client)
                    <li>
                        <a tooltip="View Client" href="{{ route('admin.clients.show', $client->id) }}" class="group rounded-full bg-amber-100 text-xs text-amber-500 py-1 px-4 transition-all ease-in-out duration-100 hover:bg-amber-500 hover:text-white">
                            <span class="text-amber-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $client->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @elseif($tutorial->services->count() > 0)
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
        @elseif($tutorial->packages->count() > 0)
            <div class="flex">
                <a tooltip="View Package" href="{{ route('admin.packages.show', $tutorial->packages->first()->id) }}" class=" group rounded-full bg-indigo-100 text-xs text-indigo-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-indigo-500 hover:text-white">
                    <x-dynamic-component :component="'svg.service.'.$tutorial->packages->first()->service->slug" class="h-4 w-4 text-indigo-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                    <span class="text-indigo-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $tutorial->packages->first()->title }}</span>
                </a>
            </div>
        @endif
    </td>
    <td class="leading-5 font-medium">

        <x-menu.three-dot :inline="true">
            @if($showMenuContent)
            <div>
                @livewire('admin.tutorials.edit', [
                    'tutorial' => $tutorial,
                    'button_type' => 'inline-menu',
                    'setService' => true,
                    'setClient' => true
                ], key('tutorial-edit'. $tutorial->id))
                <x-modal
                    wire:click="destroy()"
                    triggerText="Delete Tutorial"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete Tutorial"
                >
                    Are you sure you want to delete this Tutorial?<br><br>
                    <strong>{{ $tutorial->title }}</strong><br><br>
                    <small>
                        @if($tutorial->services->count() > 0)
                            Note: This tutorial will be deleted from the attached service and
                            all clients.
                        @elseif($tutorial->packages->count() > 0)
                            Note: This tutorial will be deleted from the package and all
                            clients.
                        @elseif($tutorial->clients->count() > 0)
                            Note: This tutorial will be deleted from all clients.
                        @endif
                    </small>
                </x-modal>
            </div>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </x-menu.three-dot>
    </td>
</tr>
