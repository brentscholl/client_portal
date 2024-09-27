@props([
'clients' => [],
'assignable_clients' => null,
'required' => false,
'uid' => rand(),
])

<div>
    <label id="client-select-label-{{ $uid }}" class="{{ $required ? 'required' : '' }}">
        Assign to Clients
    </label>
    <ul class="mt-3 flex space-x-2">
        @forelse($assignable_clients as $client)
            @if(in_array($client->id, $client_ids))
                <li class="flex justify-start group relative py-0.5 px-2 rounded-full bg-gray-200">
                    <a href="{{ route('admin.clients.show', $client->id) }}" class="flex items-center space-x-3 text-gray-300">
                        <div>
                            <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $client->title }}</div>
                        </div>
                    </a>
                    <button
                        title="Remove"
                        wire:click="unassignClient({{ $client->id }})"
                        class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                        style="top: -0.3rem; left: -0.3rem;">
                        <x-svg.x/>
                    </button>
                </li>
            @endif
        @empty
            <p class="text-xs text-gray-300">No clients assigned</p>
        @endforelse
    </ul>
    <x-modal
        type="add"
        cancelText="Done"
        triggerClass="flex text-xs text-gray-400 mt-4 rounded-md border border-gray-300 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
        modalTitle="Add Client"
        :showSubmitBtn="false"
        wire:click="load"
    >
        <x-slot name="triggerText">
            <x-svg.plus-circle class="h-4 w-4 mr-2"/>
            Add Client
        </x-slot>
        <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
            <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{ now() }}">
                @forelse( $assignable_clients as $client )
                    <li class="py-4 flex items-center justify-between w-full">
                        <label
                            for="{{ $client->id }}"
                            class="flex items-center cursor-pointer"
                        >
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $client->title }}</p>
                            </div>
                        </label>
                        <div>
                            @if(in_array($client->id, $client_ids))
                                <div x-data="{ loading: false }">
                                    <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                        <x-svg.check class="w-5 h-5 inline-block"/>
                                    </span>
                                </div>
                            @else
                                <div x-data="{ loading: false }">
                                    <button
                                        wire:click="assignClient({{ $client->id }})"
                                        type="button"
                                        @click="loading = true"
                                        x-bind:disabled="loading"
                                        class="rounded-full border border-secondary-500 bg-white text-secondary-500 p-2 flex items-center justify-center h-8 w-8 hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                    >
                                        <span x-cloak x-show="loading == false">
                                            <x-svg.plus class="w-5 h-5"/>
                                        </span>
                                        <span x-show="loading == true">
                                            <x-svg.spinner class="w-5 h-5"/>
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </li>
                @empty
                    No clients available
                @endforelse
            </ul>
        </div>
    </x-modal>
</div>
