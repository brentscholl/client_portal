<section class="mt-8 xl:mt-10">
    <x-card :opened="$cardOpened">
        <x-card.header title="Tasks" count="{{ $tasks->total() }}">
            @livewire('admin.tasks.create', [
                'client' => $client,
                'projects' => $projects,
                'project' => $project,
                'phase' => $phase,
                'team' => $team,
                'setClient' => $setClient,
                'setProject' => $setProject,
                'setPhase' => $setPhase
                ], key('create'))
        </x-card.header>
        <x-card.body :cardOpened="$cardOpened">
            <div>
                @if($cardOpened)
                    <div class="pb-6 bg-white">
                        <div class="max-w-7xl mx-auto">
                            <div class="flex space-between space-x-2 items-center">
                                <nav class="flex flex-grow-1 w-full space-x-4" aria-label="Tabs">
                                    <button
                                        type="button"
                                        wire:click="updateStatus('all')"
                                        @if($status == 'all')
                                        class="bg-secondary-100 text-secondary-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @else
                                        class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @endif
                                    >
                                        All
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="updateStatus('pending')"
                                        @if($status == 'pending')
                                        class="bg-gray-100 text-gray-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @else
                                        class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @endif
                                    >
                                        Pending
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="updateStatus('in-progress')"
                                        @if($status == 'in-progress')
                                        class="bg-secondary-100 text-secondary-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @else
                                        class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @endif
                                    >
                                        In Progress
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="updateStatus('completed')"
                                        @if($status == 'completed')
                                        class="bg-green-100 text-green-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @else
                                        class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @endif
                                    >
                                        Completed
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="updateStatus('on-hold')"
                                        @if($status == 'on-hold')
                                        class="bg-amber-100 text-amber-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @else
                                        class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @endif
                                    >
                                        On Hold
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="updateStatus('canceled')"
                                        @if($status == 'canceled')
                                        class="bg-red-100 text-red-800 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @else
                                        class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md focus:outline-none"
                                        @endif
                                    >
                                        Canceled
                                    </button>

                                </nav>
                                <div>
                                    <div class="hidden ml-6 bg-gray-100 p-0.5 rounded-lg items-center sm:flex">
                                        <button tooltip="Show visible Tasks" wire:click="setVisibleFilter('visible')" type="button" class="@if($filter_visible == 'visible') bg-white shadow-sm text-green-500 @else text-gray-400 hover:bg-white hover:shadow-sm @endif p-1.5 rounded-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500">
                                            <x-svg.eye class="h-4 w-4"/>
                                        </button>
                                        <button tooltip="Show hidden Tasks" wire:click="setVisibleFilter('hidden')" type="button" class="@if($filter_visible == 'hidden') bg-white shadow-sm text-red-500 @else text-gray-400 hover:bg-white hover:shadow-sm @endif ml-0.5 p-1.5 rounded-md text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500">
                                            <x-svg.eye-off class="h-4 w-4"/>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @if($tasks->count() > 0)
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            @foreach($tasks as $task)
                                @livewire('admin.tasks.task-card', ['task' => $task, 'projects' => $projects], key($task->id))
                            @endforeach
                        </div>
                        @if($tasks->hasMorePages())
                            <div class="max-w-2xl mx-auto px-4 mb-4 mt-4">
                                <div class="relative">
                                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                        <div class="w-full border-t border-gray-200"></div>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <button wire:click="loadMore()"
                                            type="button"
                                            class="inline-flex items-center shadow-sm px-2 py-0.5 border border-gray-200 text-xs font-medium rounded-full text-gray-400 bg-white hover:bg-secondary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                        >
                                            <svg class="-ml-1.5 mr-1 h-3 w-3 text-gray-400" x-description="Heroicon name: solid/plus-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Show More</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <span class="relative block w-full border-2 border-gray-200 border-dashed rounded-lg p-12 text-center">
                            <x-svg.task class="mx-auto h-12 w-12 text-gray-200"/>
                            <span class="mt-2 block text-sm font-medium text-gray-300">
                              No tasks assigned
                            </span>
                        </span>
                    @endif
                @else
                    <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
                @endif
            </div>
        </x-card.body>
    </x-card>
</section>

