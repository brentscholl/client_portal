<div
    class="relative rounded-lg border {{ $question_answered ? 'border-green-300' : 'border-gray-200' }} bg-white pr-6 pl-8 py-2 shadow-sm">
    <!-- Card Icon -->
    <div
        class="rounded-full bg-white border {{ $question_answered ? 'border-green-300' : 'border-gray-200' }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center"
        style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.question class="h-4 w-4 text-gray-500"/>
    </div>

    <!---- Answer Mark ---->
    @if($question_answered)
        <span
            class="flex flex-shrink-0 absolute left-2 inline-block px-1 py-0.5 focus:outline-none text-green-500 text-xs font-medium bg-white"
            style="top: -0.90em;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Answered</span>
        </span>
    @endif

    <div class="flex flex-col space-y-3">

        <!---- Question Top Container ---->
        <div class="flex space-x-2 justify-between">

            <!---- Question Type ---->
            <div class="flex-1 flex-col space-y-2 min-w-0">
                <div class="flex-1 flex space-x-3 min-w-0 items-center">
                    <p class="text-xs text-left text-gray-500 inline-flex items-center">
                        @switch($question->type)
                            @case('detail')
                            Provide Details
                            @break
                            @case('multi_choice')
                            Multiple Choice
                            @break
                            @case('select')
                            Select
                            @break
                            @case('boolean')
                            Yes or No
                            @break
                        @endswitch
                    </p>
                    @if($question->add_file_uploader)
                        <div tooltip="File Uploader added to Question">
                            <svg tooltip="This Question includes a file uploader" title="File Uploader Added"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <p class="text-sm text-left px-4 py-2 rounded-md bg-secondary-100 text-secondary-500">
                    <a tooltip="View Question"
                        class="text-secondary-500 font-medium hover:text-gray-500 transition-all duration-100 ease-in-out"
                        href="{{ route('admin.questions.show', $question->id) }}">
                        {{ $question->body }}
                    </a>
                </p>
                @if($question->tagline)
                    <p class="text-xs flex space-x-2 items-center bg-gray-100 text-gray-500 rounded-md py-2 px-2">
                        <x-svg.info-circle class="w-4 h-4 text-gray-400"/>
                        <span>{{ $question->tagline }}</span>
                    </p>
                @endif
            </div>

            <!---- Question Choices ---->
            @if($question->type != 'detail')
                <div class="flex-2 flex-col space-y-2 min-w-0 border-l-2 pl-2 border-dashed border-gray-100">
                    <div class="flex-1 flex space-x-3 min-w-0">
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
                                <li class="rounded-full bg-gray-100 text-xs text-gray-900 py-1 px-4 mb-2">{{ $choice }}</li>
                            @endforeach
                            @break
                            @case('boolean')
                            <li class="rounded-full bg-gray-100 text-xs text-gray-900 py-1 px-4 mb-2">Yes</li>
                            <li class="rounded-full bg-gray-100 text-xs text-gray-900 py-1 px-4 mb-2">No</li>
                            @break
                        @endswitch
                    </ul>
                </div>
            @endif
        </div><!-- top container ends -->

        <!---- Question Answer ---->
        @if( $question_answered )
            <div class="flex-1 flex items items-center space-x-3 min-w-0 border-t-2 pt-2 border-dashed border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 text-green-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-left text-green-500 truncate inline-flex items-center">
                    Answer:
                </p>
            </div>
            <div class="flex-1 flex flex-col space-y-2 min-w-0">
                <div class="w-full px-4 rounded-md bg-green-400 text-white">
                    <div>
                        @if($question->type == 'detail')
                            @if($answer->answer)
                                <p class="mb-2 text-white pt-2">{{ $answer->answer }}</p>
                            @endif
                        @else
                            <div class="flex space-x-2 pt-2">
                                @foreach(json_decode($answer->choices) as $choice)
                                    <div
                                        class="rounded-full bg-green-500 border border-green-400 text-xs text-white py-1 px-4 mb-2">{{ $choice }}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div>
                        @if(($answer->choices && $question->add_file_uploader) || ($answer->answer && $question->add_file_uploader))
                            <hr class="border-dashed mb-0"/>
                        @endif
                    </div>
                    <div>
                        @if($question->add_file_uploader)
                            @if($answer->files->count() > 0)
                                <div class="flex flex-wrap pt-2">
                                    @foreach($answer->files as $file)
                                        <div
                                            class="relative mr-4 flex items-center space-x-2 rounded-lg bg-green-500 border border-green-400 text-xs text-white py-1 px-3 mb-2">
                                            <a tooltip="View File information"
                                                href="{{ route('admin.files.show', $file->id) }}"
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
                    <a tooltip="View User" href="{{ route('admin.users.show', $answer->user->id) }}"
                        class="text-gray-600 hover:text-secondary-500 transition-all duration-100 ease-in-out">{{ $answer->user->fullname }}</a>
                    <span class="text-gray-400">{{ $answer->created_at->diffForHumans() }}</span></p>
            </div>
        @endif

        <div class="flex justify-between items-center min-w-0 border-t-2 border-dashed pt-2 space-x-2 border-t-2">
            <!---- Question Services ----->
            @if($question->is_onboarding)
                <div class="flex-1 flex min-w-0">
                    <div class="flex justify-between">
                        <div class="flex-1 flex items items-center space-x-3 min-w-0">
                            <div tooltip="This Question will be shown to all Clients"
                                class=" group rounded-full bg-purple-100 text-xs text-purple-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-purple-500 hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                </svg>
                                <span
                                    class="text-purple-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">Onboarding Question</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if( $question->services->count() > 0 )
                <div class="flex-1 flex min-w-0">
                    <div class="flex justify-between">
                        <div class="flex-1 flex items items-center space-x-3 min-w-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                                Services attached to:
                            </p>
                            <ul class="flex space-x-2">
                                @foreach($question->services as $service)
                                    <li>
                                        <a tooltip="View Service"
                                            href="{{ route('admin.services.show', $service->id) }}"
                                            class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                            <x-dynamic-component :component="'svg.service.'.$service->slug"
                                                class="h-4 w-4 text-secondary-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                                            <span
                                                class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $service->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if( $question->packages->count() > 0 )
                <div class="flex items items-center space-x-3 min-w-0">
                    <x-svg.package class="h-5 w-5 text-gray-400"/>
                    <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                        Attached
                        to&nbsp;<a tooltip="View Package's Service"
                            href="{{ route('admin.services.show', $question->packages->first()->service->id) }}"
                            class="font-bold hover:text-secondary-500">{{ $question->packages->first()->service->title }}</a>&nbsp;Package:
                    </p>
                    <ul class="flex space-x-2">
                        <li>
                            <a tooltip="View Package"
                                href="{{ route('admin.packages.show', $question->packages->first()->id) }}"
                                class=" group rounded-full bg-indigo-100 text-xs text-indigo-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-indigo-500 hover:text-white">
                                <x-dynamic-component
                                    :component="'svg.service.'.$question->packages->first()->service->slug"
                                    class="h-4 w-4 text-indigo-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                                <span
                                    class="text-indigo-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $question->packages->first()->title }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif


        <!---- Question Client ---->
            @if( $question->clients->count() > 0 )
                <div class="flex-1 flex min-w-0">
                    <div class="flex-1 flex items-center space-x-3 min-w-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                            Unique question for client:
                        </p>
                        <a tooltip="View Client"
                            href="{{ route('admin.clients.show', $question->clients->first()->id) }}"
                            class="group rounded-full bg-amber-100 text-xs text-amber-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                            <span
                                class="text-amber-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $question->clients->first()->title }}</span>
                        </a>
                    </div>
                </div>
            @endif

            @if( $question->projects->count() > 0 )
                <div class="flex items items-center space-x-3 min-w-0">
                    <x-svg.project class="h-5 w-5 text-gray-400"/>
                    <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                        Attached
                        to&nbsp;<a tooltip="View Project's Client"
                            href="{{ route('admin.clients.show', $question->projects->first()->client->id) }}"
                            class="font-bold hover:text-secondary-500">{{ $question->projects->first()->client->title }}</a>&nbsp;Project:
                    </p>
                    <ul class="flex space-x-2">
                        <li>
                            <a tooltip="View Project"
                                href="{{ route('admin.projects.show', $question->projects->first()->id) }}"
                                class=" group rounded-full bg-blue-100 text-xs text-blue-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-blue-500 hover:text-white">
                                <span
                                    class="text-blue-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $question->projects->first()->title }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @endif

            @if( $question->teams->count() > 0 )
                <div class="flex flex-col items-center space-x-3 min-w-0">
                    <div class="flex justify-between">
                        <div class="flex-1 flex items items-center space-x-3 min-w-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                                Teams attached to:
                            </p>
                            <ul class="flex space-x-2">
                                @foreach($question->teams as $team)
                                    <li>
                                        <a tooltip="View Team" href="{{ route('admin.teams.show', $team->id) }}"
                                            class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                            <span
                                                class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $team->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!--- Menu ---->
    <x-menu.three-dot>
        <div>
            @if($showMenuContent)
                @livewire('admin.questions.edit', [
                    'question' => $question,
                    'button_type' => 'inline-menu',
                    'setService' =>  $setService,
                    'setClient' => $setClient,
                    'setProject' => $setProject,
                    'setPackage' => $setPackage
                    ], key('question-edit-' . $question->id))
                @if($client_id && !$question_answered)
                    @livewire('admin.answers.create', ['question' => $question, 'client_id' =>  $client_id, 'button_type' => 'inline-menu', ], key('answer-create-' . $question->id))
                @endif
                @if($question_answered)
                    @livewire('admin.answers.edit', ['question' => $question, 'question_answer' => $answer, 'client_id' =>  $client_id, 'button_type' => 'inline-menu', ], key('answer-edit-' . $question->id))
                @endif
                @if($question_answered)
                    <x-modal
                        wire:click="deleteAnswer"
                        triggerText="Delete Answer"
                        triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                        modalTitle="Delete Answer"
                    >
                        Are you sure you want to delete the answer for<br> <strong>{{ $question->body }}</strong>?
                    </x-modal>
                @endif
                <x-modal
                    wire:click="destroy"
                    triggerText="Delete Question"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete Question"
                >
                    Are you sure you want to delete this Question?<br><br>
                    <strong>{{ $question->body }}</strong>
                    <div class="rounded-md p-3 bg-gray-100 mt-4">
                        <strong>What will also be deleted:</strong>
                        <ul class="ml-4 mb-4 mt-2 text-xs list-outside list-disc text-gray-500">
                            <li>Answers</li>
                            <li>Files</li>
                        </ul>
                        <small>
                            @if($question->services->count() > 0)
                                Note: This question will be deleted from the attached services and all clients.
                            @elseif($question->packages->count() > 0)
                                Note: This question will be deleted from the package and all clients.
                            @elseif($question->clients->count() > 0)
                                Note: This question will be deleted from the client.
                            @endif
                        </small>
                    </div>
                </x-modal>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </div>
    </x-menu.three-dot>
</div>
