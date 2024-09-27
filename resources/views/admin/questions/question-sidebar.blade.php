<aside class="hidden xl:block xl:pl-8">
    <div class="space-y-5">
        <!-- Sidebar Item -->
        @if($question->add_file_uploader)
            <div class="flex items-center space-x-2">
                <svg title="File Uploader Added" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Question has file uploader</span>
            </div>
        @endif

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-gray-900 text-sm font-medium">
                Type:<span class="font-bold">
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
                </span>
            </span>
        </div>

        <!-- Sidebar Item -->
        @if( $question->clients->count() > 0 )
            <div class="flex items-center space-x-2">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Question for <strong>{{ Str::plural('Client', $question->clients->count()) }}</strong>:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($question->clients as $client)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Question's Service" tooltip-p="left" href="{{ route('admin.clients.show', $client->id) }}" class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $client->title }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sidebar Item -->
        @if($question->projects->count() > 0)
            <div class="flex items-center space-x-2">
                <x-svg.project class="h-5 w-5 text-gray-400" />
                <span class="text-gray-900 text-sm font-medium">Question for {{ $question->projects->count() }} <strong>{{ Str::plural('Project', $question->projects->count()) }}</strong>:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($question->projects as $project)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Question's Project" tooltip-p="left" href="{{ route('admin.projects.show', $project->id) }}" class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $project->title }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sidebar Item -->
        @if($question->services->count() > 0)
            <div class="flex items-center space-x-2">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-900 text-sm font-medium">Question for {{ $question->services->count() }} <strong>{{ Str::plural('Service', $question->services->count()) }}</strong>:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($question->services as $service)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Question's Service" tooltip-p="left" href="{{ route('admin.services.show', $service->id) }}" class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div class="flex-shrink-0">
                                    <x-dynamic-component :component="'svg.service.'.$service->slug" class="h-5 w-5 text-gray-500" />
                                </div>
                                <div>
                                    <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $service->title }}</div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sidebar Item -->
        @if($question->packages->count() > 0)
            <div class="flex items-center space-x-2">
                <x-svg.package class="h-5 w-5 text-gray-400" />
                <span class="text-gray-900 text-sm font-medium">Question for {{ $question->packages->count() }} <strong>{{ Str::plural('Package', $question->packages->count()) }}</strong>:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($question->packages as $package)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Question's Package" tooltip-p="left" href="{{ route('admin.packages.show', $package->id) }}" class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $package->title }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-gray-900 text-sm font-medium">Answers: <strong>{{ $question->answers->count() }}</strong></span>
        </div>

        <!-- Sidebar Item -->
        @if($question->teams->count() > 0)
            <div class="flex items-center space-x-2">
                <x-svg.team class="h-5 w-5 text-gray-400" />
                <span class="text-gray-900 text-sm font-medium">Assigned to {{ Str::plural('Team', $question->teams->count()) }}:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($question->teams as $team)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Question's Team" tooltip-p="left" href="{{ route('admin.teams.show', $team->id) }}" class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $team->title }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: calendar"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-900 text-sm font-medium">Created on <time
                    datetime="2020-12-02">{{ date_format($question->created_at, 'M d, Y @ h:ia') }}</time></span>
        </div>

        <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Question"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Question</span>
            </x-slot>
            Are you sure you want to delete this Question?<br>
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
    </div>
</aside>
