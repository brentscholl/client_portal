<div
    class="relative rounded-lg border {{ $question_answered ? 'border-green-300' : 'border-gray-200 pulse-border-secondary' }} bg-white pr-6 pl-8 py-2 shadow-sm w-full">
    <!-- Card Icon -->
    <div
        class="rounded-full bg-white border {{ $question_answered ? 'border-green-300' : 'border-gray-200' }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center"
        style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.question class="h-4 w-4 text-gray-500"/>
    </div>

    <!---- Answer Mark ---->
    @if($question_answered)
        <span
            class="flex flex-shrink-0 absolute left-2 inline-block pl-1 pr-2 py-0.5 focus:outline-none rounded-full border border-green-500 text-white text-xs font-medium bg-green-500"
            style="top: -0.90em;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Answered</span>
        </span>
    @endif

    <div class="flex flex-col">

        <!---- Question Top Container ---->
        <div class="flex flex-col space-y-3">

            <!---- Question Type ---->
            <div class="flex-1 flex-col space-y-2 min-w-0 mb-3">
                <p class="text-lg text-left py-2 text-gray-900 font-medium">
                    {{ $question->body }}
                </p>
                @if($question->tagline)
                    <p class="text-xs flex space-x-2 items-center bg-gray-100 text-gray-500 rounded-md py-2 px-2">
                        <x-svg.info-circle class="w-4 h-4 text-gray-400"/>
                        <span>{{ $question->tagline }}</span>
                    </p>
                @endif
            <!---- Question Choices ---->
                @if($question->type != 'detail' && $question_answered)
                    <div class="flex items-center space-x-2">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                                Choices:
                            </p>
                        </div>
                        <ul class="flex flex-wrap space-x-2">
                            @switch($question->type)
                                @case('multi_choice')
                                @case('select')
                                @foreach(json_decode($question->choices) as $choice)
                                    <li class="rounded-full bg-gray-100 text-xs text-gray-900 py-1 px-4">{{ $choice }}</li>
                                @endforeach
                                @break
                                @case('boolean')
                                <li class="rounded-full bg-gray-100 text-xs text-gray-900 py-1 px-4">Yes</li>
                                <li class="rounded-full bg-gray-100 text-xs text-gray-900 py-1 px-4">No</li>
                                @break
                            @endswitch
                        </ul>
                    </div>
                @endif
            </div>
        </div><!-- top container ends -->

        <!---- Question Answer ---->
        @if( $question_answered && ! $is_editing )
            <div
                class="flex-1 flex items items-center space-x-1 justify-between min-w-0 border-t-2 pt-3 mb-2 border-dashed border-gray-100">
                <div class="flex items items-center space-x-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-bold text-left text-green-500 truncate inline-flex items-center">
                        Answer:
                    </p>
                </div>
                <div class="flex space-x-2">
                    <button
                        tooltip="Edit Answer"
                        type="button"
                        wire:click="editAnswer"
                        class="rounded-full h-6 w-6 flex justify-center items-center bg-secondary-100 text-secondary-500 hover:bg-secondary-500 hover:text-white"
                    >
                        <x-svg.edit class="h-4 w-4"/>
                    </button>
                    <span tooltip="Delete Answer">
                        <x-modal
                            wire:click="deleteAnswer"
                            triggerClass="rounded-full h-6 w-6 flex justify-center items-center bg-red-100 text-red-500 hover:bg-red-500 hover:text-white"
                            modalTitle="Delete Answer"
                        >
                            <x-slot name="triggerText">
                                <x-svg.trash class="h-4 w-4"/>
                            </x-slot>
                            Are you sure you want to delete this answer?<br>
                        </x-modal>
                    </span>
                </div>
            </div>
            <div class="flex-1 flex flex-col space-y-2 min-w-0">
                <div class="w-full px-4 rounded-md bg-green-400 text-white">
                    <div>
                        @if($question->type == 'detail')
                            @if($queried_answer->answer)
                                <p class="mb-2 text-white pt-2">{{ $queried_answer->answer }}</p>
                            @endif
                        @else
                            <div class="flex space-x-2 pt-2">
                                @foreach(json_decode($queried_answer->choices) as $choice)
                                    <div
                                        class="rounded-full bg-green-500 border border-green-400 text-xs text-white py-1 px-4 mb-2">{{ $choice }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div>
                        @if(($queried_answer->choices && $question->add_file_uploader) || ($queried_answer->answer && $question->add_file_uploader))
                            <hr class="border-dashed mb-0"/>
                        @endif
                    </div>
                    <div>
                        @if($question->add_file_uploader)
                            @if($queried_answer->files->count() > 0)
                                <div class="flex flex-wrap pt-2">
                                    @foreach($queried_answer->files as $file)
                                        <div
                                            class="relative mr-4 flex items-center space-x-2 rounded-lg bg-green-500 border border-green-400 text-xs text-white py-1 px-3 mb-2">
                                            <a tooltip="View File information"
                                                href="{{ route('client.files.show', $file->id) }}"
                                                class="flex items-center space-x-2 hover:text-secondary-200">
                                                <x-svg.file class="h-4 w-4"/>
                                                <span>{{ $file->src }}</span>
                                            </a>
                                            <a tooltip="Download File" class="hover:text-amber-500"
                                                href="{{ $file->url() }}" download="{{ $file->src }}">
                                                <x-svg.download class="h-4 w-4"/>
                                            </a>
                                            <x-modal
                                                wire:click="deleteFile({{ $file->id }})"
                                                triggerClass="h-4 w-4 flex self-center -mr-2 text-red-300 hover:text-red-500"
                                                modalTitle="Delete File"
                                            >
                                                <x-slot name="triggerText">
                                                    <x-svg.trash class="h-4 w-4"/>
                                                </x-slot>
                                                Are you sure you want to delete this file?<br>
                                                <strong>{{ $file->src }}</strong>
                                            </x-modal>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex space-x-2 pt-2">
                                    <div
                                        class="relative flex items-center space-x-2 rounded-lg bg-green-500 border border-dashed border-green-400 text-xs text-white py-1 px-3 mb-2">
                                        <x-svg.file class="h-4 w-4 text-white opacity-50"/>
                                        <span class="text-xs text-white opacity-50">No files uploaded</span>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <p class="text-xs">Answered by:
                    <a tooltip="View User" href="{{ route('client.users.show', $queried_answer->user->id) }}"
                        class="text-gray-600 hover:text-secondary-500 transition-all duration-100 ease-in-out">{{ $queried_answer->user->fullname }}</a>
                    <span class="text-gray-400">{{ $queried_answer->created_at->diffForHumans() }}</span></p>
            </div>
        @else
            <div class="p-4 rounded-md border-2 border-dashed mb-4 space-y-4">
                @include('errors.list')

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
                    @if($show_file_uploader)
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
                    @else
                       <button type="button" wire:click="$set('show_file_uploader', true)"
                       class="flex space-x-2 items-center text-sm font-medium rounded-md px-2 py-1 bg-secondary-100 text-secondary-500 hover:bg-secondary-500 hover:text-white"
                       >
                           <x-svg.upload class="h-4 w-4"/>
                           <span>Upload files</span>
                       </button>
                    @endif
                @endif
                <div class="flex w-full justify-end">
                    <x-button type="button" wire:click="createAnswer" :loader="true">
                        @if($is_editing)
                            Update Answer
                        @else
                            Submit Answer
                        @endif
                    </x-button>
                </div>
            </div>
        @endif
    </div>
</div>
