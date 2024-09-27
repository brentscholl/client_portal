<aside class="hidden xl:block xl:pl-8">
    <div class="space-y-5">
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            @if($tutorial->clients->count() > 0)
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Tutorial assigned to {{ Str::plural('client', $tutorial->clients->count()) }}</span>
            @elseif($tutorial->services->count() > 0)
                <x-dynamic-component :component="'svg.service.'.$tutorial->services()->first()->slug" class="h-5 w-5 text-gray-400" />
                <span class="text-gray-900 text-sm font-medium">Tutorial assigned to <a href="{{ route('admin.services.show', $tutorial->services()->first()->id) }}" class="font-bold hover:text-secondary-500">{{ $tutorial->services()->first()->title }}</a></span>
            @elseif($tutorial->packages->count() > 0)
                <x-svg.package class="h-5 w-5 text-gray-400"/>
                <span class="text-gray-900 text-sm font-medium">Tutorial assigned to <a href="{{ route('admin.packages.show', $tutorial->packages()->first()->id) }}" class="font-bold hover:text-secondary-500">{{ $tutorial->packages()->first()->title }}</a> Package <br>
                    in <a href="{{ route('admin.services.show', $tutorial->packages()->first()->service->id) }}" class="font-bold hover:text-secondary-500">{{ $tutorial->packages()->first()->service->title }}</a> Service</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: calendar"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-900 text-sm font-medium">Created on <time
                    datetime="2020-12-02">{{ date_format($tutorial->created_at, 'M d, Y @ h:ia') }}</time></span>
        </div>

        <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Tutorial"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Tutorial</span>
            </x-slot>
            Are you sure you want to delete this Tutorial?<br>
            <div class="rounded-md p-3 bg-gray-100 mt-4">
                <small>
                    @if($tutorial->services->count() > 0)
                        Note: This tutorial will be deleted from the attached service and all clients.
                    @elseif($tutorial->packages->count() > 0)
                        Note: This tutorial will be deleted from the package and all clients.
                    @elseif($tutorial->clients->count() > 0)
                        Note: This tutorial will be deleted from all clients.
                    @endif
                </small>
            </div>
        </x-modal>
    </div>
</aside>
