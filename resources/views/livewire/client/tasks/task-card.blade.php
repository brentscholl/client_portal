<?php
    $borderColor = "border-gray-200";
    switch ($task->status) {
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
<div class="relative rounded-lg border {{ $borderColor }} bg-white pr-6 pl-8 py-3 shadow-sm w-full">
    <!-- Card Icon -->
    <div
        class="rounded-full bg-white border {{ $borderColor }} shadow-sm flex items-center justify-center absolute h-8 w-8 text-center"
        style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.task class="h-4 w-4 text-gray-500"/>
    </div>

    @if($task->priority == 2)
        <span
            class="flex-shrink-0 flex space-x-1.5 items-center absolute left-2 inline-block rounded-full border {{ $borderColor }} px-2 py-0.5 focus:outline-none text-gray-400 text-xs font-medium bg-white"
            style="top: -0.90em;">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>High Priority</span>
        </span>
    @endif
    <div class="flex-1 flex justify-between min-w-0 space-x-2">
        <a tooltip="View Task" tooltip-p="left" href="{{ route('client.tasks.show', $task->id) }}"
            class="focus:outline-none">
            <p class="text-lg font-medium text-gray-900 transition-all hover:text-secondary-500">
                {{ $task->title }}
            </p>
        </a>
        <div class="flex space-x-2 items-center">
            @livewire('admin.status-changer', ['model' => $task, 'class' => 'h-7', 'large' => true], key('task-status-updater-' . $task->id))
        </div>
    </div>

    @if($task->status == 'completed')
        <div class="flex justify-between items-center min-w-0 mt-2 space-x-2">
            <div class="flex items-center justify-between space-x-3">
                <p class="ml-auto text-xs text-green-500">
                    Completed {{ $task->completed_at ? 'on ' . $task->completed_at->format('M d, Y') : '' }}</p>
            </div>
        </div>
    @elseif($task->status == 'in-progress' || $task->status == 'pending')
        @if($task->due_date)
            <div class="flex justify-between items-center min-w-0 mt-2 space-x-2">
                <div class="flex items-center justify-between space-x-3">
                    <p class="ml-auto text-xs">Due {{ $task->due_date->diffForHumans() }}
                        | {{ $task->due_date->format('M d, Y') }}</p>
                </div>
            </div>
        @else
            <div class="flex justify-between items-center min-w-0 mt-2 space-x-2">
                <div class="flex items-center justify-between space-x-3">
                    <p class="ml-auto text-xs text-gray-300">Due date not set.</p>
                </div>
            </div>
        @endif
    @endif

</div>
