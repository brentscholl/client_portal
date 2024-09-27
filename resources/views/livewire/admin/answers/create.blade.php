<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Answer"
        tooltip-p="left"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.plus-circle class="h-5 w-5"/>
        @else
            Answer Question
        @endif
    </x-button>
    <form wire:submit.prevent="createAnswer" autocomplete="off">
        <x-slideout
            title="Answer Question"
            subtitle=""
            saveBtn="Answer"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    @if($setClient)
                        <div>
                            <x-input.client-select
                                :client="$client"
                                :clients="$clients"
                            />
                        </div>
                    @endif
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="flex-1 text-gray-900 text-base font-medium">
                            {{ $question->body }}
                        </p>
                    </div>
                    @if($question->tagline)
                    <div class="flex p-2 bg-gray-100 rounded-md">
                        <p class="flex-1 text-xs">
                            {{ $question->tagline }}
                        </p>
                    </div>
                    @endif
                    @switch($question->type)
                        @case('detail')
                            <div>
                                <x-input.textarea
                                    wire:model.defer="answer"
                                    label="Answer"
                                    placeholder=""
                                    class=""
                                />
                            </div>
                        @break
                        @case('multi_choice')
                            <x-input.checkbox-set label="Choices">
                                @forelse(json_decode($question->choices) as $choice)
                                    <x-input.checkbox
                                        wire:model.defer="choices"
                                        value="{{ $choice }}"
                                        label="{{ $choice }}"
                                    />
                                @empty
                                    <p class="text-xs">No choices available.</p>
                                @endforelse
                            </x-input.checkbox-set>
                        @break
                        @case('select')
                            <x-input.checkbox-set label="Choices">
                                @forelse(json_decode($question->choices) as $choice)
                                    <x-input.checkbox
                                        wire:model.defer="choices"
                                        type="radio"
                                        value="{{ $choice }}"
                                        label="{{ $choice }}"
                                    />
                                @empty
                                    <p class="text-xs">No choices available.</p>
                                @endforelse
                            </x-input.checkbox-set>
                        @break
                        @case('boolean')
                            <x-input.checkbox-set label="Choices">
                                <x-input.checkbox
                                    wire:model.defer="choices"
                                    type="radio"
                                    value="Yes"
                                    label="Yes"
                                />
                                <x-input.checkbox
                                    wire:model.defer="choices"
                                    type="radio"
                                    value="No"
                                    label="No"
                                />
                            </x-input.checkbox-set>
                        @break
                    @endswitch
                    @if($question->add_file_uploader)
                        <div>
                            <x-input.file-upload
                                wire:key="{{ now() }}"
                                wire:model="files"
                                label="Upload Files"
                                :required="false"
                                placeholder=""
                                multiple
                                uploader_type="file"
                                class=""/>
                        </div>
                    @endif
                @else
                    @if($setClient)
                        <x-skeleton.input.text/>
                    @endif
                    <x-skeleton.input.label />
                    @if($question->tagline)
                        <x-skeleton.input.label />
                    @endif
                    <x-skeleton.input.textarea/>
                    <x-skeleton.input.textarea/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>

