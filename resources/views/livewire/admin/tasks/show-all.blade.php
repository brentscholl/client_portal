<div>
    <div class="grid grid-cols-12 mb-4">
        <div class="col-span-1 pr-2">
            <label for="location" class="block text-sm leading-5 font-medium text-gray-700">Per Page</label>
            <select wire:model="perPage" id="location"
                class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-200 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                <option>20</option>
                <option>50</option>
                <option>100</option>
            </select>
        </div>

        <div class="col-span-3 pr-2 grid grid-cols-2">
            <div class="col-span-6 sm:col-span-2 pr-2">
                <x-input.select
                    wire:model="selectedClient"
                    label="Client"
                >
                    <x-input.option>Choose a Client</x-input.option>
                    @foreach($clients as $key => $client)
                        <x-input.option value="{{$client->id}}">{{$client->title}}</x-input.option>
                    @endforeach
                </x-input.select>
            </div>
        </div>

        <div class="col-span-3 pr-2 grid grid-cols-2">
            <div class="col-span-6 sm:col-span-2 pr-2">
                <x-input.select
                    wire:model="selectedTeam"
                    label="Team"
                >
                    <x-input.option>Choose a Team</x-input.option>
                    @foreach($teams as $key => $team)
                        <x-input.option value="{{$team->id}}">{{$team->title}}</x-input.option>
                    @endforeach
                </x-input.select>
            </div>
        </div>

        <div class="col-span-5">
            <x-input.text wire:model="search" label="Search" placeholder="Search Tasks..."/>
        </div>
    </div>
    <div class="flex flex-col">
        <div class="-my-2 pt-2 pb-8 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow sm:rounded-lg border-b border-gray-200">
                <table class="s-table">
                    <thead>
                    <tr>
                        <th scope="col">Status</th>
                        <th scope="col">
                            <button wire:click="sortBy('title')"
                                class="group flex items-center space-x-2 text-xs font-medium uppercase tracking-wider {{ $sortField == 'title' ? 'text-gray-900 underline' : 'text-gray-500' }} hover:text-gray-900 ">
                                <span>Title</span>
                                @if($sortField == 'title')
                                    @if($sortAsc)
                                        <x-svg.sort-desc class="h-3 w-3"/>
                                    @else
                                        <x-svg.sort-asc class="h-3 w-3"/>
                                    @endif
                                @else
                                    <x-svg.sort class="opacity-60 group-hover:opacity-100 h-3 w-3"/>
                                @endif
                            </button>
                        </th>
                        <th scope="col">Project</th>
                        <th scope="col">Phase</th>
                        <th scope="col">
                            <button wire:click="sortBy('due_date')"
                                class="group flex items-center space-x-2 text-xs font-medium uppercase tracking-wider {{ $sortField == 'due_date' ? 'text-gray-900 underline' : 'text-gray-500' }} hover:text-gray-900 ">
                                <span>Due Date</span>
                                @if($sortField == 'due_date')
                                    @if($sortAsc)
                                        <x-svg.sort-desc class="h-3 w-3"/>
                                    @else
                                        <x-svg.sort-asc class="h-3 w-3"/>
                                    @endif
                                @else
                                    <x-svg.sort class="opacity-60 group-hover:opacity-100 h-3 w-3"/>
                                @endif
                            </button>
                        </th>
                        <th scope="col">Client</th>
                        <th scope="col">Team</th>
                        <th scope="col"><span class="sr-only">Edit</span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        @livewire('admin.tasks.task-row', [$task], key('task-row'.$task->id))
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>

