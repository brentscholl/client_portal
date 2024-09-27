<tr>
    <td >
        <span tooltip-p="right" tooltip="{{ $project->service->title }}"><x-dynamic-component :component="'svg.service.'.$project->service->slug" class="h-5 w-5 text-gray-300"/></span>
    </td>
    <td>
        <div class="leading-5 font-medium">
            <a  tooltip="View Project" class="text-gray-900 hover:text-secondary-500" href="{{ route('admin.projects.show', $project->id) }}">{{ $project->title }}</a>
        </div>
    </td>
    <td>
        <nav aria-label="Progress">
            <ol class="flex items-center mt-4">
                @foreach($project->phases as $phase)
                    <li class="relative {{ $loop->last ? '' : 'pr-10' }}">
                        @switch($phase->status)
                            @case('completed')
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-secondary-500"></div>
                            </div>
                            <a  tooltip="View Phase {{ $phase->step }} (Completed)" href="{{ route('admin.phases.show', $phase->id) }}" class="relative w-6 h-6 flex items-center justify-center bg-secondary-500 rounded-full hover:bg-secondary-600">
                                <!-- Heroicon name: solid/check -->
                                <svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </a>
                            @break
                            @case('in-progress')
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200"></div>
                            </div>
                            <a  tooltip="View Phase {{ $phase->step }} (In Progress)" href="{{ route('admin.phases.show', $phase->id) }}" class="group relative w-6 h-6 flex items-center justify-center bg-white border-2 border-secondary-500 rounded-full hover:border-secondary-600">
                                <span class="text-xs text-secondary-500">{{ $phase->step }}</span>
                            </a>
                            @break
                            @case('pending')
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200"></div>
                            </div>
                            <a  tooltip="View Phase {{ $phase->step }} (Pending)" href="{{ route('admin.phases.show', $phase->id) }}" class="group relative w-6 h-6 flex items-center justify-center bg-white border-2 border-gray-200 rounded-full hover:border-gray-400">
                                <span class="text-xs text-gray-400">{{ $phase->step }}</span>
                            </a>
                            @break
                        @endswitch
                    </li>
                @endforeach
            </ol>
        </nav>
        <?php
        $task_completed_count = $project->tasks->where('status', 'completed')->count();
        $task_in_progress_count = $project->tasks->where('status', 'in-progress')->count();
        $task_count = $project->tasks->where('status', '!=', 'canceled')->count();
        $complete_percent = $task_count > 0 ? $task_completed_count / $task_count * 100 : 1.5;
        $in_progress_percent = $task_count > 0 ? ($task_in_progress_count + $task_completed_count) / $task_count * 100 : 1.5;
        ?>
        @if($task_count > 0)
            <p class="text-xs mt-2">Tasks Completed
                <span class="ml-2 text-secondary-500 text-sm font-semibold">{{ $task_completed_count }} <span class="text-xs text-primary-200">/ {{ $task_count }}</span></span>
            </p>
            <div class="bg-gray-100 shadow-inner rounded-full h-4 w-full mt-2 relative">
                <div class="absolute left-0 top-0 h-4 bg-secondary-500 opacity-50 rounded-full" style="width: {{ $in_progress_percent - 2 }}%;"></div>
                <div class="absolute left-0 top-0 h-4 bg-secondary-500 rounded-full" style="width: {{ $complete_percent }}%;"></div>
            </div>
        @else
            <p class="text-xs text-gray-400 mt-6 mb-4">This project does not have any tasks.</p>
        @endif
    </td>
    <td>
        <div class="text-sm text-gray-800"> {!! $project->due_date ? date_format($project->due_date, 'M d, Y') : '<small>No due date.</small>' !!}</div>
        <div class="text-sm text-gray-500">{{ $project->due_date ? $project->due_date->diffForHumans() : '' }}</div>
    </td>
    <td>
        <div class="text-sm">
            <a tooltip="View Client" class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ route('admin.clients.show', $project->client->id) }}" target="_blank">{{ $project->client->title }}</a>
        </div>
    </td>
    <td>
        <div class="text-sm">
            <a class="" href="{{ route('admin.services.show', $project->service->id) }}" target="_blank">{{ $project->service->title }}</a>
        </div>
    </td>
    <td>
        <div class="flex space-x-2 items-center">
            @livewire('admin.status-changer', ['model' => $project], key('project-status-updater-' . $project->id))
                <button
                    type="button"
                    wire:click="toggleVisibility()"
                    class="rounded-full h-5 w-5 p-1 flex justify-center items-center {{ $project->visible ? 'text-green-400 bg-green-100 hover:bg-red-100 hover:text-red-400' : 'bg-red-100 text-red-400 hover:text-green-400 hover:bg-green-100' }} transition-all duration-100 ease-in-out"
                    tooltip-p="left"
                    @if($project->visible)
                    tooltip="Project is visible to Client. Click to toggle"
                    @else
                    tooltip="Project is hidden from Client. Click to toggle"
                    @endif
                >
                    @if($project->visible)
                        <x-svg.eye class="h-4 w-4"/>
                    @else
                        <x-svg.eye-off class="h-4 w-4"/>
                    @endif
                </button>

        </div>
    </td>
    <td class="leading-5 font-medium">
        <x-menu.three-dot :inline="true">
            @if($showMenuContent)
            <div>
                @livewire('admin.projects.edit', ['project' => $project, 'button_type' => 'inline-menu'], key('project-edit-' . $project->id))
                <x-modal
                    wire:click="destroy()"
                    triggerText="Delete Project"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete Project"
                >
                    Are you sure you want to delete this Project?<br><br>
                    <strong>{{ $project->title }}</strong>
                    <div class="rounded-md p-3 bg-gray-100 mt-4">
                        <strong>What will also be deleted:</strong>
                        <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                            <li>Phases</li>
                            <li>Tasks (including Task Files)</li>
                            <li>Questions create specifically for this project (including
                                Answers, and files)
                            </li>
                            <li>Files</li>
                        </ul>
                    </div>
                </x-modal>
            </div>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </x-menu.three-dot>
    </td>
</tr>
