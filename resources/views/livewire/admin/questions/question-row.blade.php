<tr>
    <td>
        @if($question->is_onboarding)
            <span tooltip="This Question is an Onboarding Question"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg></span>
        @elseif($question->clients->count() > 0)
            <span tooltip="Attached to Client"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg></span>
        @elseif($question->services->count() > 0)
            <svg tooltip="Attached to Service" class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        @elseif($question->packages->count() > 0)
            <span tooltip="Attached to Package"><x-svg.package class="h-5 w-5 text-gray-400"/></span>
        @elseif($question->projects->count() > 0)
            <span tooltip="Attached to Project"><x-svg.project class="h-5 w-5 text-gray-400"/></span>
        @endif
    </td>
    <td>
        <div class="leading-5 font-medium">
            <a tooltip="View Question" class="text-gray-900 hover:text-secondary-500" href="{{ route('admin.questions.show', $question->id) }}">{{ $question->body }}</a>
        </div>
    </td>
    <td>
        <div class="flex space-x-2 text-xs items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
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
        </div>
    </td>
    <td>
        @if($question->is_onboarding)
            Onboarding
        @elseif($question->clients->count() > 0)
            Client
        @elseif($question->services->count() > 0)
            Services
        @elseif($question->packages->count() > 0)
            Package
        @elseif($question->projects->count() > 0)
            Project
        @endif
    </td>
    <td>
        @if($question->clients->count() > 0)
            <div class="text-sm">
                <a tooltip="View Client" class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ route('admin.clients.show', $question->clients->first()->id) }}">{{ $question->clients->first()->title }}</a>
            </div>
        @elseif($question->services->count() > 0)
            <ul class="flex flex-wrap space-x-2">
                @foreach($question->services as $service)
                    <li>
                        <a tooltip="View Service" href="{{ route('admin.services.show', $service->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                            <x-dynamic-component :component="'svg.service.'.$service->slug" class="h-4 w-4 text-secondary-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                            <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $service->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @elseif($question->packages->count() > 0)
            <div class="flex">
                <a tooltip="View Package" href="{{ route('admin.packages.show', $question->packages->first()->id) }}" class=" group rounded-full bg-indigo-100 text-xs text-indigo-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-indigo-500 hover:text-white">
                    <x-dynamic-component :component="'svg.service.'.$question->packages->first()->service->slug" class="h-4 w-4 text-indigo-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                    <span class="text-indigo-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $question->packages->first()->title }}</span>
                </a>
            </div>
        @elseif($question->projects->count() > 0)
            <div class="flex">
                <a tooltip="View Project" href="{{ route('admin.clients.show', $question->projects->first()->id) }}" class=" group rounded-full bg-blue-100 text-xs text-blue-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-blue-500 hover:text-white">
                    <x-svg.project class="h-4 w-4 text-blue-500 group-hover:text-white transition-all ease-in-out duration-100"/>
                    <span class="text-blue-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $question->projects->first()->title }}</span>
                </a>
            </div>
        @endif
    </td>
    <td>
        @if($question->teams->count() > 0)
            <ul class="flex flex-wrap space-x-2">
                @foreach($question->teams as $team)
                    <li>
                        <a tooltip="View Team" href="{{ route('admin.teams.show', $team->id) }}" class=" group rounded-full bg-gray-100 text-xs text-gray-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-gray-500 hover:text-white">
                            <span class="text-gray-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $team->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </td>
    <td class="leading-5 font-medium">
        <x-menu.three-dot :inline="true">
            @if($showMenuContent)

            <div>
                @livewire('admin.questions.edit', [
                    'question' => $question,
                    'button_type' => 'inline-menu',
                    'setService' => true,
                    'setClient' => true,
                ], key('question-edit-' . $question->id))
                <x-modal
                    wire:click="destroy()"
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
                                Note: This question will be deleted from the attached services
                                and all clients.
                            @elseif($question->packages->count() > 0)
                                Note: This question will be deleted from the package and all
                                clients.
                            @elseif($question->clients->count() > 0)
                                Note: This question will be deleted from the client.
                            @endif
                        </small>
                    </div>
                </x-modal>
            </div>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </x-menu.three-dot>
    </td>
</tr>
