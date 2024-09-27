<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'icon')
            <span tooltip="Edit File" tooltip-p="left"><x-svg.edit class="h-5 w-5"/></span>
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="saveFile" autocomplete="off">
        <x-slideout
            title="Editing File"
            subtitle="{{ Str::limit($file->src, 30) }}"
            saveBtn="Update File"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5 text-left">
                @if($slideout_open)
                    <div class="space-y-6" x-data="{ showInput: @entangle('assign_type') }" wire:ignore>
                        @php($type_index = 0)
                        @switch($assign_type)
                            @case('task')
                            @php($type_index = 0)
                            @break
                            @case('answer')
                            @php($type_index = 1)
                            @break
                            @case('resource')
                            @php($type_index = 2)
                            @break
                        @endswitch
                        <x-input.radio-set index="{{ $type_index }}" label="Assign Type">
                            <x-input.radio
                                wire:model.lazy="assign_type"
                                value="task"
                                label="Assign to a Task"
                                :first="true"
                                x-model="showInput"
                            />
                            <x-input.radio
                                wire:model.lazy="assign_type"
                                value="answer"
                                label="Assign to an Answer"
                                x-model="showInput"
                            />
                            <x-input.radio
                                wire:model.lazy="assign_type"
                                value="resource"
                                label="Set as a Resource File"
                                x-model="showInput"
                            />
                        </x-input.radio-set>

                        <div x-show="showInput == 'task' || showInput == 'answer'">
                            <x-input.client-select
                                :client="$client"
                                :clients="$clients"
                                :required="true"
                            />
                        </div>

                        <div class="space-y-6" x-show="showInput == 'task'">
                            <div>
                                <x-input.project-select
                                    :project="$project"
                                    :projects="$projects"
                                    :client="$client"
                                    :required="true"
                                />
                            </div>
                            <div>
                                <x-input.phase-select
                                    :project="$project"
                                    :phase="$phase"
                                    :required="true"
                                />
                            </div>
                            <div>
                                <x-input.task-select
                                    :task="$task"
                                    :tasks="$tasks"
                                    label="Assign to Task"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div class="space-y-6" x-show="showInput == 'answer'">
                            <div>
                                <x-input.answer-select
                                    :answer="$answer"
                                    :answers="$answers"
                                    label="Assign to Answer"
                                    :required="true"
                                />
                            </div>
                        </div>

                    </div>


                    <div>
                        <x-input.text
                            wire:model.defer="src"
                            label="File name"
                            :required="true"
                        />
                    </div>

                    <div>
                        <x-input.textarea
                            wire:model.defer="caption"
                            label="Description"
                        />
                    </div>

                @else
                    <x-skeleton.input.radio-set>
                        <x-skeleton.input.radio :first="true"/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio :last="true"/>
                    </x-skeleton.input.radio-set>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.textarea/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
