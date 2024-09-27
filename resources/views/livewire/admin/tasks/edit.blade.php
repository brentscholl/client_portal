<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit task
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="saveTask" autocomplete="off">
        <x-slideout
            title="Edit Task"
            subtitle="{{ $task->title }}"
            saveBtn="Update Task"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5 text-left">
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
                @if($setProject)
                    <div>
                        <x-input.project-select
                            :project="$project"
                            :projects="$projects"
                            :client="$client"
                            :required="true"
                        />
                    </div>
                @endif

                @if($setPhase)
                    <div>
                        <x-input.phase-select
                            :project="$project"
                            :phase="$phase"
                            :required="true"
                        />
                    </div>
                @endif

                <div>
                    <x-input.text
                        wire:model.lazy="title"
                        label="Title"
                        :required="true"
                        placeholder="ie. Provide a task title"
                        class=""
                    />
                </div>

                <div>
                    <x-input.textarea
                        wire:model.lazy="description"
                        label="Description"
                        placeholder="What should the client do?"
                        class=""
                    />
                </div>

                <div>
                    <x-input.date
                        wire:model.lazy="due_date"
                        label="Due Date"
                        placeholder=""
                        class=""
                    />
                </div>
                <div>
                    @php($type_index = $task->type == 'detail' ? 0 : 1)
                    <x-input.radio-set index="{{ $type_index }}" label="Task Type">
                        <x-input.radio
                            wire:model.lazy="task_type"
                            value="detail"
                            label="Provide Details"
                            description="Client needs to provide details to complete task"
                            :first="true"
                        />
                        <x-input.radio
                            wire:model.lazy="task_type"
                            value="approval"
                            label="Approval"
                            description="Client needs to approve the details in task"
                            :last="true"
                        />
                    </x-input.radio-set>
                </div>

                <div>
                    <label>Task Add-ons</label>
                </div>
                <div x-data="{ showInput: {{ $attach_url ? '1' : '0' }} }">
                    <x-input.checkbox
                        wire:model.defer="attach_url"
                        label="Attach Urls"
                        description="Provide urls for the client to view"
                        x-model="showInput"
                    />
                    <div x-show="showInput">
                        @foreach($urls as $url)
                            <div class="mt-4 pl-7 relative grid grid-cols-1 gap-y-6 gap-x-1 sm:grid-cols-2">
                                <x-input.text
                                    wire:model.defer="urls.{{ $loop->index }}"
                                    wire:key="url_{{ $loop->index }}"
                                    label="URL"
                                    :required="false"
                                    placeholder="https://xd.adobe.com/view/example/"
                                    class="sm:col-span-1"
                                />
                                <x-input.text
                                    wire:model.defer="url_labels.{{ $loop->index }}"
                                    wire:key="url_label_{{ $loop->index }}"
                                    label="Label"
                                    :required="false"
                                    placeholder="ie. Design Concept"
                                    class="sm:col-span-1"
                                />
                                @if(! $loop->first)
                                    <button
                                        type="button"
                                        wire:click="removeUrl('{{ $loop->index }}')"
                                        class="inline-flex text-xs w-5 h-5 p-1 absolute top-0 right-0 items-center border border-transparent rounded-full shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400"
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
                                wire:click="addNewURL"
                                type="button"
                                class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-secondary-600 hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                <svg class="h-5 w-5" x-description="Heroicon name: solid/plus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <x-input.checkbox-set>
                        <x-input.checkbox
                            wire:model.defer="add_file_uploader"
                            label="Add file uploader"
                            description="Provides a file upload so client can upload files to complete task"
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
                                    cancelText="Done"
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

                    {{--                <div x-data="{ showInput: {{ $add_dependable_task ? 'true' : 'false'}} }">--}}
{{--                    <x-input.checkbox--}}
{{--                        wire:model.defer="add_dependable_task"--}}
{{--                        label="Add Dependable Task"--}}
{{--                        description="This task can only be completed once the dependable task is completed"--}}
{{--                        x-model="showInput"--}}
{{--                    />--}}
{{--                    <div x-show="showInput">--}}
{{--                        <x-input.task-select--}}
{{--                            :tasks="$dependable_tasks"--}}
{{--                            :task="$dependable_task"--}}
{{--                            class="mt-4 pl-7"--}}
{{--                        />--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div>
                    @php($priority_index = $task->priority == 1 ? 0 : 1)
                    <x-input.radio-set index="{{ $priority_index }}" label="Task Priority">
                        <x-input.radio
                            wire:model.defer="priority"
                            value="1"
                            label="Normal"
                            :first="true"
                        />
                        <x-input.radio
                            wire:model.defer="priority"
                            value="2"
                            label="High"
                            :last="true"
                        />
                    </x-input.radio-set>
                </div>
                    <div>
                        <x-input.checkbox-set>
                            <x-input.checkbox
                                wire:model.lazy="hidden"
                                label="Hide Task from Client"
                                description="Task will be created, but not visible to Client."
                            >
                                <x-slot name="label">
                                    <x-svg.eye-off class="h-5 w-5 inline-block mr-1"/>Hide Task from Client
                                </x-slot>
                            </x-input.checkbox>
                        </x-input.checkbox-set>
                    </div>
                @else
                    @if($setClient)
                        <x-skeleton.input.text />
                    @endif
                    @if($setProject)
                        <x-skeleton.input.text />
                    @endif

                    @if($setPhase)
                        <x-skeleton.input.text />
                    @endif

                    <x-skeleton.input.text />

                    <x-skeleton.input.textarea />

                    <x-skeleton.input.text />

                    <x-skeleton.input.radio-set>
                        <x-skeleton.input.radio :first="true" />
                        <x-skeleton.input.radio :last="true" />
                    </x-skeleton.input.radio-set>

                    <x-skeleton.input.label />

                    <x-skeleton.input.checkbox />
                    <x-skeleton.input.checkbox />
                    <x-skeleton.input.checkbox />

                    <x-skeleton.input.radio-set>
                        <x-skeleton.input.radio :first="true"/>
                        <x-skeleton.input.radio :last="true"/>
                    </x-skeleton.input.radio-set>
                    <x-skeleton.input.checkbox />
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
