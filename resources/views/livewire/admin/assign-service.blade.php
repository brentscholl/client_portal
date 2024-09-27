<div>
    <h2 class="text-sm font-medium text-gray-500">{{ Str::plural('Service', $client->services->count()) }}</h2>
    <ul class="mt-3 space-y-3">
        @forelse($client->services as $service)
            <li class="flex justify-start group relative">

                    <a href="{{ route('admin.services.show', $service->id) }}" class="flex items-center space-x-3 text-gray-300"
                        tooltip-p="left"
                        @if(!in_array($service->id, $project_service_ids))
                        tooltip="This Client does not have a Project assigned to this Service."
                        @else
                        tooltip="This Client has a Project assigned to this Service. Remove/change Project to remove this Service."
                        @endif
                    >
                        <div class="flex-shrink-0">
                            <x-dynamic-component :component="'svg.service.'.$service->slug" class="h-10 w-10 mr-2 text-gray-300" />
                        </div>
                        <div>
                            <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $service->title }}</div>
                        </div>
                    </a>
                @if(!in_array($service->id, $project_service_ids))
                    <button
                        tooltip="Remove Service from Client"
                        wire:click="unassign({{ $service->id }})"
                        class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                        style="top: -0.3rem; left: -0.3rem;">
                        <x-svg.x/>
                    </button>
                @endif
            </li>
        @empty
            <p class="text-xs text-gray-300">No Services assigned</p>
        @endforelse
    </ul>
    <x-modal
        type="add"
        cancelText="Done"
        triggerClass="flex text-xs text-gray-400 mt-4 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
        modalTitle="Add Service"
        :showSubmitBtn="false"
        wire:click="load"
        :load="true"
    >
        <x-slot name="triggerText">
            <x-svg.plus-circle class="h-4 w-4 mr-2"/>
            Add Service
        </x-slot>
        <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
            @if($modalOpen)
                <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{now()}}">
                    @forelse($assignable_services as $service)
                        <li class="py-4 flex items-center justify-between w-full">
                            <label
                                for="{{ $service->id }}"
                                class="flex items-center cursor-pointer"
                            >
                                <x-dynamic-component :component="'svg.service.'.$service->slug" class="h-10 w-10 mr-2 text-gray-300" />
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $service->title }}</p>
                                </div>
                            </label>
                            <div>
                                @if(in_array($service->id, $service_ids))
                                    <div x-data="{ loading: false }">
                                    <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                        <x-svg.check class="w-5 h-5 inline-block"/>
                                    </span>
                                    </div>
                                @else
                                    <div x-data="{ loading: false }">
                                        <button
                                            wire:click="assign({{ $service->id }})"
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
                        No services available
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
