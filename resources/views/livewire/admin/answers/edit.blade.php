<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit Answer
        @else
            Edit Answer
        @endif
    </x-button>
    <form wire:submit.prevent="saveAnswer" autocomplete="off">
        <x-slideout
            title="Editing Answer"
            subtitle="{{ Str::limit($question_answer->answer, 30) }}"
            saveBtn="Update Answer"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5 text-left">
                @if($slideout_open)
                    <div class="flex p-2 bg-gray-100 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="flex-1 text-sm">
                            {{ $question->body }}
                        </p>
                    </div>
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
                    @if($question_answer->files->count() > 0)
                        <div>
                            <label>Answer's Existing Files</label>
                            <div class="mt-1 flex flex-wrap bg-gray-100 rounded-md pt-4 px-4">
                                @foreach($question_answer->files as $file)
                                    <div class="relative mr-4 flex items-center space-x-2 rounded-lg bg-gray-100 border border-gray-200 text-xs text-gray-500 py-1 px-3 mb-4">
                                        <a href="{{ route('admin.files.show', $file->id) }}" class="flex items-center space-x-2 hover:text-secondary-500">
                                            <x-svg.file class="h-4 w-4"/>
                                            <span>{{ $file->src }}</span>
                                        </a>
                                        <a class="hover:text-amber-500" href="{{ $file->url() }}" download="{{ $file->src }}">
                                            <x-svg.download class="h-4 w-4"/>
                                        </a>
                                        <x-modal
                                            wire:click="deleteFile({{ $file->id }})"
                                            triggerClass="h-4 w-4 flex self-center -mr-2 text-red-300 hover:text-red-500"
                                            modalTitle="Delete File"
                                        >
                                            <x-slot name="triggerText">
                                                <x-svg.trash class="h-4 w-4" />
                                            </x-slot>
                                            Are you sure you want to delete this file?<br> <strong>{{ $file->src }}</strong>
                                        </x-modal>
                                    </div>
                                @endforeach
                            </div>
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
                    @if($question_answer->files->count() > 0)
                        <x-skeleton.input.textarea/>
                    @endif
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
