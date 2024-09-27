<x-slideout.wrapper>
    <x-button
        type="button"
        btn="secondary"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Tutorial"
        tooltip-p="left"
    >
        <x-svg.plus-circle class="h-5 w-5 text-white"/>
    </x-button>
    <form wire:submit.prevent="createTutorial" autocomplete="off">
        <x-slideout
            title="Create a new Tutorial"
            subtitle="{{ optional($service)->title ?  optional($service)->title : (optional($client)->title ? optional($client)->title : '') }}"
            saveBtn="Create Tutorial"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    @if($setClient || $setService || $setPackage)
                        <div x-data="{ showInput: @entangle('assign_type') }">
                            @if($setClient && $setService)
                                <x-input.radio-set label="Assign Type">
                                    @if($setClient)
                                        <x-input.radio
                                            wire:model="assign_type"
                                            value="client"
                                            label="Assign to a Client"
                                            description="Assign tutorial to 1 or more clients."
                                            :first="true"
                                            x-model="showInput"
                                            :last="(!$setPackage && !$setService)"
                                        />
                                    @endif
                                    @if($setService)
                                        <x-input.radio
                                            wire:model="assign_type"
                                            value="service"
                                            label="Assign to Services"
                                            description="Make tutorial unique to a service."
                                            :first="(!$setClient)"
                                            :last="(!$setPackage)"
                                        />
                                    @endif
                                    @if($setPackage)
                                        <x-input.radio
                                            wire:model="assign_type"
                                            value="package"
                                            label="Assign to Package"
                                            description="Make tutorial unique to a service package."
                                            :first="(!$setClient && !$setPackage)"
                                            :last="true"
                                        />
                                    @endif
                                </x-input.radio-set>
                            @endif
                            @if($setClient)
                                <div class="mt-6" x-show="showInput == 'client'">
                                    <div>
                                        <label id="client-select-label-{{ rand() }}" class="">
                                            Assign to Client
                                        </label>
                                        <ul class="mt-3 flex flex-wrap items-center">
                                            @if($assignable_clients)
                                                @php($a_i = 0)
                                                @foreach($assignable_clients as $assignable_client)
                                                    @if(in_array($assignable_client->id, $client_ids))
                                                        <li class="flex justify-start group relative py-0.5 px-3 leading-4 rounded-full bg-gray-200 mb-3 mr-3">
                                                            <div class="flex items-center space-x-3 text-gray-300">
                                                                <div class="text-sm font-medium transition text-gray-900">{{ $assignable_client->title }}</div>
                                                            </div>
                                                            <button
                                                                type="button"
                                                                title="Remove"
                                                                wire:click="unassignClient({{ $assignable_client->id }})"
                                                                class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                                                                style="top: -0.3rem; right: -0.3rem;">
                                                                <x-svg.x/>
                                                            </button>
                                                        </li>
                                                        @php($a_i++)
                                                    @endif
                                                @endforeach
                                                @if($a_i == 0)
                                                    <p class="text-xs text-gray-300 mb-2">No clients assigned</p>
                                                @endif
                                            @else
                                                <p class="text-xs text-gray-300 mb-2">No clients assigned</p>
                                            @endif
                                        </ul>
                                        <x-modal
                                            type="add"
                                            cancelText="Cancel"
                                            triggerClass="flex text-xs text-gray-400 mt-2 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
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
                                                    @forelse( $assignable_clients ?: [] as $assignable_client )
                                                        <li class="py-4 flex items-center justify-between w-full">
                                                            <label
                                                                for="{{ $assignable_client->id }}"
                                                                class="flex items-center cursor-pointer"
                                                            >
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-900">{{ $assignable_client->title }}</p>
                                                                </div>
                                                            </label>
                                                            <div>
                                                                @if(in_array($assignable_client->id, $client_ids))
                                                                    <div x-data="{ loading: false }">
                                                                <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                                                    <x-svg.check class="w-5 h-5 inline-block"/>
                                                                </span>
                                                                    </div>
                                                                @else
                                                                    <div x-data="{ loading: false }">
                                                                        <button
                                                                            wire:click="assignClient({{ $assignable_client->id }})"
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
                                </div>
                            @endif
                            @if($setService)
                                <div class="mt-6" x-show="showInput == 'service'">
                                    <div>
                                        <x-input.service-select
                                            :service="$service"
                                            :services="$services"
                                        />
                                    </div>
                                </div>
                            @endif
                            @if($setPackage)
                                @if($setService)
                                    <div class="mt-6" x-show="showInput == 'package'">
                                        <div>
                                            <x-input.service-select
                                                :services="$services"
                                                :service="$service"
                                                :require="true"
                                            />
                                        </div>
                                    </div>
                                @endif
                                <div class="mt-6" x-show="showInput == 'package'">
                                    <div>
                                        <x-input.package-select
                                            :package="$package"
                                            :packages="$packages"
                                            :service="$service"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    <div>
                        <x-input.text
                            wire:model.defer="title"
                            label="Title"
                            placeholder=""
                            class=""
                            :required="true"
                        />
                    </div>
                    <div>
                        <x-input.textarea
                            wire:model.defer="body"
                            label="Description"
                            placeholder=""
                            class=""
                        />
                    </div>
                    <div>
                        <x-input.text
                            wire:model.defer="video_url"
                            label="Loom Video Url"
                            placeholder="https://www.loom.com/share/123abc"
                            class=""
                            :required="true"
                        />
                    </div>
                @else
                    @if($setClient && $setService)
                        <x-skeleton.input.radio-set>
                            <x-skeleton.input.radio :first="true"/>
                            <x-skeleton.input.radio :last="true"/>
                        </x-skeleton.input.radio-set>
                        <x-skeleton.input.text/>
                    @endif

                    <x-skeleton.input.text/>
                    <x-skeleton.input.textarea/>
                    <x-skeleton.input.text/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>

