<x-slideout.wrapper>
    <x-button
        type="button"
        btn="secondary"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Question"
        tooltip-p="left"
    >
        <x-svg.plus-circle class="h-5 w-5 text-white"/>
    </x-button>
    <form wire:submit.prevent="createQuestion" autocomplete="off">
        <x-slideout
            title="Create a new Question"
            subtitle="{{ optional($service)->title ?  optional($service)->title : (optional($client)->title ? optional($client)->title : '') }}{{ optional($package)->title ? ' - ' . optional($package)->title : '' }}{{ optional($project)->title ? ' - ' . optional($project)->title : '' }}"
            saveBtn="Create Question"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    @if($setClient || $setService || $setPackage || $setProject)
                        <div x-data="{ showInput: @entangle('assign_type') }">
                            <x-input.radio-set label="Assign Type">
                                @if($setClient)
                                    <x-input.radio
                                        wire:model="assign_type"
                                        value="client"
                                        label="Assign to a Client"
                                        description="Make question unique to a client"
                                        :first="true"
                                        x-model="showInput"
                                        :last="(!$setPackage && !$setService && !$setProject)"
                                    />
                                @endif
                                @if($setProject)
                                    <x-input.radio
                                        wire:model="assign_type"
                                        value="project"
                                        label="Assign to Project"
                                        description="Make question unique to a project"
                                        :first="!$setClient"
                                        :last="(!$setPackage && !$setService)"
                                    />
                                @endif
                                @if($setService)
                                    <x-input.radio
                                        wire:model="assign_type"
                                        value="service"
                                        label="Assign to Services"
                                        description="Assign question to 1 or more services. This question will be presented to all clients with the services"
                                        :first="(!$setClient && !$setProject)"
                                        :last="(!$setPackage)"
                                    />
                                @endif
                                @if($setPackage)
                                    <x-input.radio
                                        wire:model="assign_type"
                                        value="package"
                                        label="Assign to Package"
                                        description="Assign question to a package. This question will be presented to all clients with the package"
                                        :first="(!$setClient && !$setProject && !$setPackage)"
                                    />
                                @endif
                                @if($setOnboarding)
                                    <x-input.radio
                                        wire:model="assign_type"
                                        value="onboarding"
                                        label="Assign to Onboarding"
                                        description="Assign question as an onboarding question. This question will be presented to all clients by default."
                                        :first="(!$setClient && !$setProject && !$setPackage)"
                                        :last="true"
                                    />
                                @endif
                            </x-input.radio-set>
                            @if($setClient)
                                <div class="mt-6" x-show="showInput == 'client' || showInput == 'project'">
                                    <div>
                                        <x-input.client-select
                                            :client="$client"
                                            :clients="$clients"
                                            :required="true"
                                        />
                                    </div>
                                </div>
                            @endif
                            @if($setProject)
                                <div class="mt-6" x-show="showInput == 'project'">
                                    <div>
                                        <x-input.project-select
                                            :project="$project"
                                            :projects="$projects"
                                            :client="$client"
                                            :required="true"
                                        />
                                    </div>
                                </div>
                            @endif
                            @if($setService)
                                <div class="mt-6" x-show="showInput == 'service'">
                                    <div>
                                        <label id="client-select-label-{{ rand() }}" class="required">
                                            Assign to Services
                                        </label>
                                        <ul class="mt-3 flex flex-wrap items-center">
                                            @if($assignable_services)
                                                @php($a_i = 0)
                                                @foreach($assignable_services as $assignable_service)
                                                    @if(in_array($assignable_service->id, $service_ids))
                                                        <li class="flex justify-start group relative py-0.5 px-3 leading-4 rounded-full bg-gray-200 mb-3 mr-3">
                                                            <div class="flex items-center space-x-3 text-gray-300">
                                                                <x-dynamic-component :component="'svg.service.'.$assignable_service->slug" class="h-6 w-6 text-gray-300"/>
                                                                <div class="text-sm font-medium transition text-gray-900">{{ $assignable_service->title }}</div>
                                                            </div>
                                                            <button
                                                                type="button"
                                                                title="Remove"
                                                                wire:click="unassignService({{ $assignable_service->id }})"
                                                                class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                                                                style="top: -0.3rem; right: -0.3rem;">
                                                                <x-svg.x/>
                                                            </button>
                                                        </li>
                                                        @php($a_i++)
                                                    @endif
                                                @endforeach
                                                @if($a_i == 0)
                                                    <p class="text-xs text-gray-300 mb-2">No services assigned</p>
                                                @endif
                                            @else
                                                <p class="text-xs text-gray-300 mb-2">No services assigned</p>
                                            @endif
                                        </ul>
                                        <x-modal
                                            type="add"
                                            cancelText="Cancel"
                                            triggerClass="flex text-xs text-gray-400 mt-2 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
                                            modalTitle="Add Service"
                                            :showSubmitBtn="false"
                                            wire:click="load"
                                        >
                                            <x-slot name="triggerText">
                                                <x-svg.plus-circle class="h-4 w-4 mr-2"/>
                                                Add Service
                                            </x-slot>
                                            <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
                                                <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{ now() }}">
                                                    @forelse( $assignable_services ?: [] as $assignable_service )
                                                        <li class="py-4 flex items-center justify-between w-full">
                                                            <label
                                                                for="{{ $assignable_service->id }}"
                                                                class="flex items-center cursor-pointer"
                                                            >
                                                                <x-dynamic-component :component="'svg.service.'.$assignable_service->slug" class="h-10 w-10 mr-2 text-gray-300"/>
                                                                <div class="ml-3">
                                                                    <p class="text-sm font-medium text-gray-900">{{ $assignable_service->title }}</p>
                                                                </div>
                                                            </label>
                                                            <div>
                                                                @if(in_array($assignable_service->id, $service_ids))
                                                                    <div x-data="{ loading: false }">
                                                                <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                                                    <x-svg.check class="w-5 h-5 inline-block"/>
                                                                </span>
                                                                    </div>
                                                                @else
                                                                    <div x-data="{ loading: false }">
                                                                        <button
                                                                            wire:click="assignService({{ $assignable_service->id }})"
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
                                            </div>
                                        </x-modal>
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
                                            :required="true"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    <div>
                        <x-input.textarea
                            wire:model.defer="body"
                            label="Question"
                            placeholder=""
                            class=""
                            :required="true"
                        />
                    </div>
                    <div>
                        <x-input.text
                            wire:model.defer="tagline"
                            label="Tagline (optional)"
                            placeholder=""
                            class=""
                        />
                    </div>
                    <div x-data="{ showInput: @entangle('question_type') }">
                        <x-input.radio-set label="Question Type">
                            <x-input.radio
                                wire:model="question_type"
                                value="detail"
                                label="Provide Details"
                                description="Client needs to provide details to answer question"
                                :first="true"
                                x-model="showInput"
                            />
                            <x-input.radio
                                wire:model="question_type"
                                value="multi_choice"
                                label="Multiple Choice"
                                description="Client can select multiple answers from the provided choices"
                            />
                            <x-input.radio
                                wire:model="question_type"
                                value="select"
                                label="Select"
                                description="Client needs to select a single answer from the provided choices"
                            />
                            <x-input.radio
                                wire:model="question_type"
                                value="boolean"
                                label="Yes or No"
                                description="Client needs to select Yes or No"
                                :last="true"
                            />
                        </x-input.radio-set>
                        <div x-show="showInput == 'multi_choice' || showInput == 'select'">
                            @foreach($choices as $choice)
                                <div class="mt-4 relative flex items-center space-x-2">
                                    <x-input.text
                                        wire:model.defer="choices.{{ $loop->index }}"
                                        wire:key="choice_{{ $loop->index }}"
                                        label="Choice"
                                        :required="false"
                                        placeholder=""
                                        class="flex-grow-1 w-full"
                                    />
                                    @if(! $loop->first)
                                        <button
                                            type="button"
                                            wire:click="removeChoice('{{ $loop->index }}')"
                                            class="inline-flex text-xs w-5 h-5 p-1 mt-6 items-center border border-transparent rounded-full shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                            <div class="mt-3 text-right">
                                <button
                                    wire:click="addNewChoice"
                                    type="button"
                                    class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                    <svg class="h-5 w-5" x-description="Heroicon name: solid/plus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <x-labeled-divider label="Question Add-ons"/>
                    <div>
                        <x-input.checkbox-set>
                            <x-input.checkbox
                                wire:model.lazy="add_file_uploader"
                                label="Add file uploader"
                                description="Provides a file upload so client can upload files to answer question"
                            />
                        </x-input.checkbox-set>
                    </div>
                    <div>
                        <div x-data="{ showInput: @entangle('assign_to_team') }">
                            <x-input.checkbox
                                wire:model.lazy="assign_to_team"
                                value="true"
                                label="Assign to Teams"
                                description="Choice teams that require to know the answer to this question"
                                x-model="showInput"
                            />
                            <div x-show="showInput" class="ml-7">

                                <label id="client-select-label-{{ rand() }}" class="mt-3">
                                    Teams:
                                </label>
                                <ul class="mt-3 flex flex-wrap items-center">
                                    @if($assignable_teams)
                                        @php($a_i = 0)
                                        @foreach($assignable_teams as $assignable_team)
                                            @if(in_array($assignable_team->id, $team_ids))
                                                <li class="flex justify-start group relative py-0.5 px-3 leading-4 rounded-full bg-gray-200 mb-3 mr-3">
                                                    <div class="text-sm font-medium transition text-gray-900">{{ $assignable_team->title }}</div>
                                                    <button
                                                        type="button"
                                                        title="Remove"
                                                        wire:click="unassignTeam({{ $assignable_team->id }})"
                                                        class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                                                        style="top: -0.3rem; right: -0.3rem;">
                                                        <x-svg.x/>
                                                    </button>
                                                </li>
                                                @php($a_i++)
                                            @endif
                                        @endforeach
                                        @if($a_i == 0)
                                            <p class="text-xs text-gray-300 mb-2">No teams assigned</p>
                                        @endif
                                    @else
                                        <p class="text-xs text-gray-300 mb-2">No teams assigned</p>
                                    @endif
                                </ul>
                                <x-modal
                                    type="add"
                                    cancelText="Cancel"
                                    triggerClass="flex text-xs text-gray-400 mt-2 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
                                    modalTitle="Add Team"
                                    :showSubmitBtn="false"
                                    wire:click="load"
                                >
                                    <x-slot name="triggerText">
                                        <x-svg.plus-circle class="h-4 w-4 mr-2"/>
                                        Add Team
                                    </x-slot>
                                    <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
                                        <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{ now() }}">
                                            @forelse( $assignable_teams ?: [] as $assignable_team )
                                                <li class="py-4 flex items-center justify-between w-full">
                                                    <label
                                                        for="{{ $assignable_team->id }}"
                                                        class="flex items-center cursor-pointer"
                                                    >
                                                        <p class="text-sm font-medium text-gray-900">{{ $assignable_team->title }}</p>
                                                    </label>
                                                    <div>
                                                        @if(in_array($assignable_team->id, $team_ids))
                                                            <div x-data="{ loading: false }">
                                                        <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                                            <x-svg.check class="w-5 h-5 inline-block"/>
                                                        </span>
                                                            </div>
                                                        @else
                                                            <div x-data="{ loading: false }">
                                                                <button
                                                                    wire:click="assignTeam({{ $assignable_team->id }})"
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
                                                No teams available
                                            @endforelse
                                        </ul>
                                    </div>
                                </x-modal>
                            </div>
                        </div>
                    </div>
                @else
                    @if($setClient && $setService)
                        <x-skeleton.input.radio-set>
                            <x-skeleton.input.radio :first="true"/>
                            <x-skeleton.input.radio/>
                            <x-skeleton.input.radio/>
                            <x-skeleton.input.radio :last="true"/>
                        </x-skeleton.input.radio-set>
                        <x-skeleton.input.text/>
                    @endif

                    <x-skeleton.input.textarea/>
                    <x-skeleton.input.text/>

                    <x-skeleton.input.radio-set>
                        <x-skeleton.input.radio :first="true"/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio :last="true"/>
                    </x-skeleton.input.radio-set>

                    <x-skeleton.input.label/>
                    <x-skeleton.input.checkbox/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>

