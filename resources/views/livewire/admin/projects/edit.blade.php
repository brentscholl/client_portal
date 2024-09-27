<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit project
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="saveProject" autocomplete="off">
        <x-slideout
            title="Edit Project"
            subtitle="{{ $project->title }}"
            saveBtn="Update Project"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)

                    @if($setClient)
                        <div>
                            <x-input.client-select
                                :client="$client"
                                :clients="$clients"
                                :required="true"
                            />
                        </div>
                    @endif
                        @if($setService)
                            <div>
                                <x-input.service-select
                                    :service="$service"
                                    :services="$services"
                                    :required="true"
                                />
                            </div>
                        @endif
                        @if($setPackage)
                            <div class="mt-6">
                                <div>
                                    <label id="client-select-label-{{ rand() }}" class="">
                                        Add Packages
                                    </label>
                                    @if($service)
                                        <ul class="mt-3 flex flex-wrap items-center">
                                            @if($assignable_packages)
                                                @php($a_i = 0)
                                                @foreach($assignable_packages as $assignable_package)
                                                    @if(in_array($assignable_package->id, $package_ids))
                                                        <li class="flex justify-start group relative py-0.5 px-3 leading-4 rounded-full bg-gray-200 mb-3 mr-3">
                                                            <div class="flex items-center space-x-3 text-gray-300">
                                                                <x-svg.package class="h-6 w-6 text-gray-300"/>
                                                                <div class="text-sm font-medium transition text-gray-900">{{ $assignable_package->title }}</div>
                                                            </div>
                                                            <button
                                                                type="button"
                                                                title="Remove"
                                                                wire:click="unassignPackage({{ $assignable_package->id }})"
                                                                class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                                                                style="top: -0.3rem; right: -0.3rem;">
                                                                <x-svg.x/>
                                                            </button>
                                                        </li>
                                                        @php($a_i++)
                                                    @endif
                                                @endforeach
                                                @if($a_i == 0)
                                                    <p class="text-xs text-gray-300 mb-2">No packages added</p>
                                                @endif
                                            @else
                                                <p class="text-xs text-gray-300 mb-2">No packages added</p>
                                            @endif
                                        </ul>
                                        <x-modal
                                            type="add"
                                            cancelText="Done"
                                            triggerClass="flex text-xs text-gray-400 mt-2 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
                                            modalTitle="Add Package"
                                            :showSubmitBtn="false"
                                            wire:click="load"
                                        >
                                            <x-slot name="triggerText">
                                                <x-svg.plus-circle class="h-4 w-4 mr-2"/>
                                                Add Package
                                            </x-slot>
                                            <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
                                                <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{ now() }}">
                                                    @forelse( $assignable_packages ?: [] as $assignable_package )
                                                        <li class="py-4 flex items-center justify-between w-full">
                                                            <label
                                                                for="{{ $assignable_package->id }}"
                                                                class="flex items-center cursor-pointer"
                                                            >
                                                                <x-svg.package class="h-10 w-10 mr-2 text-gray-300"/>
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-900">{{ $assignable_package->title }}</p>
                                                                </div>
                                                            </label>
                                                            <div>
                                                                @if(in_array($assignable_package->id, $package_ids))
                                                                    <div x-data="{ loading: false }">
                                                                <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                                                    <x-svg.check class="w-5 h-5 inline-block"/>
                                                                </span>
                                                                    </div>
                                                                @else
                                                                    <div x-data="{ loading: false }">
                                                                        <button
                                                                            wire:click="assignPackage({{ $assignable_package->id }})"
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
                                                        No packages available
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </x-modal>
                                    @else
                                        <p class="text-gray-300 text-xs">Pick a service first</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    <div>
                        <x-input.text
                            wire:model.defer="title"
                            label="Title"
                            :required="true"
                            placeholder=""
                            class=""
                        />
                    </div>

                    <div>
                        <x-input.textarea
                            wire:model.defer="description"
                            label="Description"
                            placeholder=""
                            class=""
                        />
                    </div>

                    <div>
                        <x-input.date
                            wire:model.defer="due_date"
                            label="Due Date"
                            :required="false"
                            placeholder=""
                            class=""
                        />
                    </div>

                    <x-labeled-divider label="Project Add-ons"/>

                    <x-input.urls :urls="$urls"/>

                        <div>
                            <x-input.checkbox-set>
                                <x-input.checkbox
                                    wire:model.lazy="hidden"
                                    label="Hide Project from Client"
                                    description="Project will be created, but not visible to Client."
                                >
                                    <x-slot name="label">
                                        <x-svg.eye-off class="h-5 w-5 inline-block mr-1"/>Hide Project from Client
                                    </x-slot>
                                </x-input.checkbox>
                            </x-input.checkbox-set>
                        </div>
                @else
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.textarea/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.label/>
                    <x-skeleton.input.checkbox/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
