<tr>
    <td>
        @livewire('admin.status-changer', ['model' => $task], key('task-status-updater-' . $task->id))
    </td>
    <td>
        <div class="leading-5 font-medium">
            <a tooltip="View Task" class="text-gray-900 hover:text-secondary-500" href="{{ route('admin.tasks.show', $task->id) }}">{{ $task->title }}</a>
        </div>
    </td>
    <td>
        <div class="text-sm">
            <a tooltip="View Task's Project" class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ route('admin.projects.show', $task->project->id) }}" target="_blank">{{ $task->project->title }}</a>
        </div>
    </td>
    <td>
        <div class="text-sm">
            <a tooltip="View Task's Phase" class="text-gray-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ route('admin.phases.show', $task->phase->id) }}" target="_blank">
                <span class="text-xs uppercase">Phase: {{ $task->phase->step }}</span>
                <br>{{ $task->phase->title }}</a></div>
    </td>
    <td>
        @if($task->due_date)
            <div class="text-sm text-gray-800">{{ date_format($task->due_date, 'M d, Y') }}</div>
            <div class="text-sm text-gray-500">{{ $task->due_date->diffForHumans() }}</div>
        @else
            <div class="text-xs text-gray-300">Due date not set</div>
        @endif
    </td>
    <td>
        <div class="text-sm">
            <a tooltip="View Task's Client" class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ route('admin.clients.show', $task->client->id) }}" target="_blank">{{ $task->client->title }}</a>
        </div>
    </td>
    <td>
        @if($task->teams->count() > 0)
            <ul class="flex flex-wrap space-x-2">
                @foreach($task->teams as $team)
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
        <div class="flex space-x-2 items-center">
                <button
                    type="button"
                    wire:click="toggleVisibility()"
                    class="rounded-full h-5 w-5 p-1 flex justify-center items-center {{ $task->visible ? 'text-green-400 bg-green-100 hover:bg-red-100 hover:text-red-400' : 'bg-red-100 text-red-400 hover:text-green-400 hover:bg-green-100' }} transition-all duration-100 ease-in-out"
                    tooltip-p="left"
                    @if($task->visible)
                    tooltip="Task is visible to Client. Click to toggle"
                    @else
                    tooltip="Task is hidden from Client. Click to toggle"
                    @endif
                >
                    @if($task->visible)
                        <x-svg.eye class="h-4 w-4"/>
                    @else
                        <x-svg.eye-off class="h-4 w-4"/>
                    @endif
                </button>

            <x-menu.three-dot :inline="true">
                @if($showMenuContent)
                <div>
                    @livewire('admin.tasks.edit', ['task' => $task, 'button_type' => 'inline-menu'], key('task-edit-' . $task->id))
                    <x-modal
                        wire:click="destroy()"
                        triggerText="Delete Task"
                        triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                        modalTitle="Delete Task"
                    >
                        Are you sure you want to delete this task?<br><br>
                        <strong>{{ $task->title }}</strong>
                        <div class="rounded-md p-3 bg-gray-100 mt-4">
                            <strong>What will also be deleted:</strong>
                            <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                                <li>Files</li>
                            </ul>
                        </div>
                    </x-modal>
                </div>
                @else
                    <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
                @endif
            </x-menu.three-dot>
        </div>
    </td>
</tr>
