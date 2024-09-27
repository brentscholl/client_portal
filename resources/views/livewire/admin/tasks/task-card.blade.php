<?php
    $borderColor = "border-gray-200";
    switch ( $task->status ) {
        case('in-progress'):
            $borderColor = 'border-secondary-300';
            break;
        case('completed'):
            $borderColor = 'border-green-300';
            break;
        case('on-hold'):
            $borderColor = 'border-amber-300';
            break;
        case('canceled'):
            $borderColor = 'border-red-300';
            break;
    }
?>
<div class="relative rounded-lg border {{ $borderColor }} bg-white pr-6 pl-8 py-3 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border {{ $borderColor }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.task class="h-4 w-4 text-gray-500"/>
    </div>

    @if($task->priority == 2)
        <span class="flex-shrink-0 absolute left-2 inline-block px-1 py-0.5 focus:outline-none text-gray-400 text-xs font-medium bg-white" style="top: -0.90em;">
            High Priority
        </span>
    @endif
    <div class="flex justify-between items-center space-x-3">
        <div class="flex items-center space-x-3 text-gray-300">
            @switch($task->type)
                @case('detail')
                <svg tooltip="This is a Detail Task" title="Detail Task" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                @break
                @case('approval')
                <svg tooltip="This is an Approval Task" title="Approval Task" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                @break
            @endswitch
            @if($task->add_file_uploader)
                <svg tooltip="This Task includes a file uploader" title="File Uploader Added" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
            @endif
            @if($task->dependable_task_id)
                <svg title="Dependable Task" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            @endif
        </div>
        <div class="flex space-x-2 items-center">
            @livewire('admin.status-changer', ['model' => $task, 'class' => 'h-7'], key('task-status-updater-' . $task->id))
            <button
                type="button"
                wire:click="toggleVisibility"
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

        </div>
    </div>
    <div class="flex-1 min-w-0 border-t border-dashed pt-2 mt-2 space-x-2">
        <a tooltip="View Task" tooltip-p="left" href="{{ route('admin.tasks.show', $task->id) }}" class="focus:outline-none">
            <p class="text-sm font-medium text-gray-900 transition-all hover:text-secondary-500">
                {{ $task->title }}
            </p>
        </a>
    </div>
    <div class="flex justify-between items-center min-w-0 border-t border-dashed pt-2 mt-2 space-x-2">
        @if( $task->teams->count() > 0 )
            <div class="flex-1 flex items items-center space-x-3 min-w-0">
                <x-svg.team class="h-5 w-5 text-gray-400"/>
                <ul class="flex space-x-2">
                    @foreach($task->teams as $team)
                        <li>
                            <a tooltip="View Task's Team" href="{{ route('admin.teams.show', $team->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $team->title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="flex items-center justify-between space-x-3">
            @if($task->status == 'completed')
                <p class="ml-auto text-xs text-green-500">Completed {{ $task->completed_at ? 'on ' . $task->completed_at->format('M d, Y') : '' }}</p>
            @elseif($task->status == 'in-progress' || $task->status == 'pending')
                @if($task->due_date)
                    <p class="ml-auto text-xs">Due {{ $task->due_date->diffForHumans() }}
                        | {{ $task->due_date->format('M d, Y') }}</p>
                @else
                    <p class="ml-auto text-xs text-gray-300">Due date not set.</p>
                @endif
            @endif
        </div>
    </div>
    <x-menu.three-dot>
        <div>
            @if($showMenuContent)
                @livewire('admin.tasks.edit', ['task' => $task, 'button_type' => 'inline-menu'], key('task-edit-' . $task->id))
                <x-modal
                    wire:click="destroy"
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
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </div>
    </x-menu.three-dot>
</div>
