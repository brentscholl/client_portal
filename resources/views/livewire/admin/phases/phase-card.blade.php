<li wire:sortable.item="{{ $phase->id }}" class="relative transition-all {{ $last ? '' : 'pb-10' }}">
    @if(! $last)
        @switch($phase->status)
            @case('completed')
            <div class="phase-line -ml-px absolute -mt-8 top-1/2 left-4 w-px h-full bg-secondary-500" aria-hidden="true"></div>
            @break
            @case('in-progress')
            <div class="phase-line -ml-px absolute -mt-8 top-1/2 left-4 w-px h-full bg-gray-300" aria-hidden="true"></div>
            @break
            @default()
            <div class="phase-line -ml-px absolute -mt-8 top-1/2 left-4 w-px h-full bg-gray-300" aria-hidden="true"></div>
            @break
        @endswitch
    @endif
    <div class="relative flex items-center group">
    @php($titleClass = '')
    @switch($phase->status)
        @case('completed')
        <!-- Complete Step -->
            <span tooltip="Phase Step Completed. Drag and Drop to reorder" tooltip-p="right" class="h-9 flex items-center">
                  <span wire:sortable.handle class="relative z-10 w-8 h-8 flex items-center justify-center bg-secondary-500 rounded-full cursor-move">
                        <!-- Heroicon name: solid/check -->
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                  </span>
            </span>
        @break
        @case('in-progress')
        @php($titleClass = 'text-secondary-500')

        <!-- Current Step -->
            <span tooltip="Phase Step In Progress. Drag and Drop to reorder" tooltip-p="right" class="h-9 flex items-center" aria-hidden="true">
                  <span wire:sortable.handle class="relative z-10 w-8 h-8 flex items-center justify-center bg-white border-2 border-secondary-500 rounded-full cursor-move">
                        <span class="text-sm text-secondary-500">{{ $phase->step }}</span>
                  </span>
            </span>
        @break
        @default()
        @php($titleClass = 'text-gray-500')

        <!-- Upcoming Step -->
            <span tooltip="Phase Step Pending. Drag and Drop to reorder" tooltip-p="right" class="h-9 flex items-center" aria-hidden="true">
                  <span wire:sortable.handle class="relative z-10 w-8 h-8 flex items-center justify-center bg-white border-2 border-gray-200 rounded-full cursor-move">
                        <span class="text-sm text-gray-400">{{ $phase->step }}</span>
                  </span>
            </span>
            @break
        @endswitch
        @php($borderColor = "border-gray-200")
        @switch($phase->status)
            @case('completed')
            @php($borderColor = "border-secondary-500")
            <div class="-mt-px absolute ml-0.5 left-4 h-px w-10 bg-secondary-500" aria-hidden="true"></div>
            @break
            @case('in-progress')
            @php($borderColor = "border-gray-200 shadow-md pulse-border-secondary")
            <div class="-mt-px absolute ml-0.5 left-4 h-px w-10 bg-secondary-500" aria-hidden="true"></div>
            @break
            @default()
            <div class="-mt-px absolute ml-0.5 left-4 h-px w-10 bg-gray-300" aria-hidden="true"></div>
        @break
    @endswitch

    <!---- Phase Item ---->
        <div class="w-full relative rounded-lg border {{ $borderColor }} bg-white ml-4 px-6 py-4 transition-all">
            <div class="flex items-center space-x-3">
                <div class="flex-1 min-w-0">
                    <a tooltip="View Phase" tooltip-p="left" href="{{ route('admin.phases.show', $phase->id) }}" class="text-sm font-semibold tracking-wide uppercase {{ $titleClass }} hover:text-secondary-500 transition-all">{{ $phase->title ? $phase->title : 'Phase: ' . $phase->step }}</a>
                </div>
                <div class="flex space-x-2 items-center">
                    @livewire('admin.status-changer', ['model' => $phase, 'class' => 'h-7'], key('phase-status-changer-' . $phase->id))
                        <button
                            type="button"
                            wire:click="toggleVisibility"
                            class="rounded-full h-5 w-5 p-1 flex justify-center items-center {{ $phase->visible ? 'text-green-400 bg-green-100 hover:bg-red-100 hover:text-red-400' : 'bg-red-100 text-red-400 hover:text-green-400 hover:bg-green-100' }} transition-all duration-100 ease-in-out"
                            tooltip-p="left"
                            @if($phase->visible)
                            tooltip="Phase is visible to Client. Click to toggle"
                            @else
                            tooltip="Phase is hidden from Client. Click to toggle"
                            @endif
                        >
                            @if($phase->visible)
                                <x-svg.eye class="h-4 w-4"/>
                            @else
                                <x-svg.eye-off class="h-4 w-4"/>
                            @endif
                        </button>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <?php
                $task_completed_count = $phase->tasks->filter(function ($item){
                    return $item->status == 'completed';
                })->count();
                $task_in_progress_count = $phase->tasks->filter(function ($item){
                    return $item->status == 'in-progress';
                })->count();
                $task_count = $phase->tasks->filter(function ($item){
                    return $item->status != 'canceled';
                })->count();
                $complete_percent =  $task_count > 0 ? $task_completed_count / $task_count * 100 : 1.5;
                $in_progress_percent =  $task_count > 0 ? ($task_in_progress_count + $task_completed_count) / $task_count * 100 : 1.5;
                ?>
                @if($task_count > 0)
                    <div class="inline-flex justify-between items-center w-full">
                        <p class="text-xs">Tasks Completed <span class="ml-2 text-secondary-500 text-sm font-semibold">{{ $task_completed_count }} <span class="text-xs text-primary-200">/ {{ $task_count }}</span></span></p>
                        @if($phase->status == 'completed')
                            <p class="text-xs text-left text-gray-500 truncate">Completed on {{ $phase->due_date->format('M d, Y') }}</p>
                        @elseif($phase->status == 'in-progress' || $phase->status == 'pending')
                            @if($phase->due_date)
                                <p class="text-xs text-left text-gray-500 truncate">Due {{ $phase->due_date->diffForHumans() }} | {{ $phase->due_date->format('M d, Y') }}</p>
                            @else
                                <p class="text-xs text-left text-gray-500 truncate">Due date not set.</p>
                            @endif
                        @endif
                    </div>
                    <div class="bg-gray-100 shadow-inner rounded-full h-4 w-full mt-4 relative">
                        <div tooltip="{{ $task_in_progress_count }} {{ Str::plural('Task', $task_in_progress_count) }} In Progress" tooltip-p="right" class="absolute left-0 top-0 h-4 bg-secondary-500 opacity-50 rounded-full" style="width: {{ $in_progress_percent - 2 }}%;"></div>
                        <div tooltip="{{ $task_completed_count }} {{ Str::plural('Task', $task_completed_count) }} Completed" tooltip-p="right" class="absolute left-0 top-0 h-4 bg-secondary-500 rounded-full" style="width: {{ $complete_percent }}%;"></div>
                    </div>
                @else
                    <p class="text-xs text-gray-400 mt-6 mb-4">This phase does not have any tasks.</p>
                @endif
            </div>
            <x-menu.three-dot>
                <div>
                @if($showMenuContent)
                    @livewire('admin.phases.edit', ['phase' => $phase, 'button_type' => 'inline-menu'], key('phase-edit-' . $phase->id))
                    <x-modal
                        wire:click="destroy"
                        triggerText="Delete Phase"
                        triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                        modalTitle="Delete Phase"
                    >
                        Are you sure you want to delete this Phase?<br><br>
                        <strong>{{ $phase->description ? $phase->description : 'Phase: ' . $phase->title }}</strong>
                        <div class="rounded-md p-3 bg-gray-100 mt-4">
                            <strong>What will also be deleted:</strong>
                            <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                                <li>Tasks (including Task Files)</li>
                            </ul>
                        </div>
                    </x-modal>
                @else
                    <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
                @endif
                </div>
            </x-menu.three-dot>
        </div>
    </div>
</li>
