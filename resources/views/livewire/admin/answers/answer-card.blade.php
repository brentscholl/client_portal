<div class="relative rounded-lg border border-green-300 bg-white pr-6 pl-8 py-5 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border border-green-300 shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </div>

    <!---- Answer Mark ---->
    <span class="flex flex-shrink-0 absolute left-2 inline-block px-1 py-0.5 focus:outline-none text-green-500 text-xs font-medium bg-white" style="top: -0.90em;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Answer</span>
    </span>

    <div class="flex flex-col space-y-3">
        <div class="flex-1 flex flex-col space-y-3 min-w-0 border-b-2 pb-3 border-dashed border-gray-100">
            <div class="flex-1 flex items-center space-x-3 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                    Client:
                </p>
                <a tooltip="View Answer's Client" href="{{ route('admin.clients.show', $answer->client->id) }}" class="group rounded-full bg-amber-100 text-xs text-amber-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                    <span class="text-amber-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $answer->client->title }}</span>
                </a>
            </div>
        </div>

        <!---- Question Answer ---->
        <div class="flex-1 flex flex-col space-y-3 min-w-0">
            <div tooltip="Answer provided" class="w-full px-4 rounded-md bg-green-500 text-white">
                @if($question->type == 'detail')
                    @if($answer->answer)
                        <p class="mb-4 text-white pt-4">{{ $answer->answer }}</p>
                    @endif
                @else
                    <div class="flex space-x-2 pt-4">
                        @foreach(json_decode($answer->choices) as $choice)
                            <div class="rounded-full bg-green-500 border border-green-400 text-xs text-white py-1 px-4 mb-4">{{ $choice }}</div>
                        @endforeach
                    </div>

                @endif
                @if(($answer->choices && $question->add_file_uploader) || ($answer->answer && $question->add_file_uploader))
                    <hr class="border-dashed mb-0"/>
                @endif
                @if($question->add_file_uploader)
                    @if($answer->files->count() > 0)
                        <div class="flex flex-wrap pt-4">
                            @foreach($answer->files as $file)
                                <div class="relative mr-4 flex items-center space-x-2 rounded-lg bg-green-500 border border-green-400 text-xs text-white py-1 px-3 mb-4">
                                    <a tooltip="View File details" href="{{ route('admin.files.show', $file->id) }}" class="flex items-center space-x-2 hover:text-secondary-200">
                                        <x-svg.file class="h-4 w-4"/>
                                        <span>{{ $file->src }}</span>
                                    </a>
                                    <a tooltip="Download File" class="hover:text-amber-500" href="{{ $file->url() }}" download="{{ $file->src }}">
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
                                        Are you sure you want to delete this File?<br><br>
                                        <strong>{{ $file->src }}</strong>
                                    </x-modal>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex space-x-2 pt-4">
                            <div class="relative flex items-center space-x-2 rounded-lg bg-green-500 border border-dashed border-green-400 text-xs text-white py-1 px-3 mb-4">
                                <x-svg.file class="h-4 w-4 text-white opacity-50"/>
                                <span class="text-xs text-white opacity-50">No files uploaded</span>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <p class="text-xs">Answered by:
                <a tooltip="View User" href="{{ route('admin.users.show', $answer->user->id) }}" class="text-gray-600 hover:text-secondary-500 transition-all duration-100 ease-in-out">{{ $answer->user->fullname }}</a>
                <span class="text-gray-400">{{ $answer->created_at->diffForHumans() }}</span></p>
        </div>

    </div>

    <!--- Menu ---->
    <x-menu.three-dot>
        <div>
            @if($showMenuContent)
                @livewire('admin.answers.edit', ['question' => $question, 'question_answer' => $answer, 'client_id' =>  $answer->client_id, 'button_type' => 'inline-menu', ], key('answer-edit-' . $question->id))

                <x-modal
                    wire:click="deleteAnswer"
                    triggerText="Delete Answer"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete Answer"
                >
                    Are you sure you want to delete this answer?<br><br>
                    <strong>{{ Str::limit($answer->answer, '50') }}</strong>
                </x-modal>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </div>
    </x-menu.three-dot>
</div>

