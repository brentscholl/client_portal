<div>
    <x-modal
        type="add"
        cancelText="Done"
        triggerClass="group inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white gradient-secondary transition-all duration-300 ease-in-out hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
        modalTitle="Add Service"
        :showSubmitBtn="false"
        wire:click="load"
        :load="true"
    >
        <x-slot name="triggerText">
            <x-svg.plus-circle class="h-5 w-5"/>
        </x-slot>
        <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
            @if($modalOpen)
                <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{now()}}">
                    @forelse($assignable_clients as $client)
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
                                            wire:click="assign({{ $client->id }})"
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
            @else
                <div class="pr-14 my-6 w-full">
                    <x-svg.spinner class="h-8 w-8 text-secondary-500 mx-auto"/>
                </div>
            @endif
        </div>
    </x-modal>
</div>
