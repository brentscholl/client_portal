<div class="task task--{{ $task_data['status'] }}">

@if($task_data['priority'] == 2)
<span class="task__priority" style="top: -0.90em;">
High Priority
</span>
@endif
<div class="task__title">
<div class="task__title__inner">
{{ $task_data['title'] }}
</div>
</div>
<div class="task__contents">
<div class="task__contents__due-date">
@if($task_data['status'] == 'completed')
<div class="task__contents__due-date__label--completed">Completed on {{ $task_data['completed_at'] }}</div>
@elseif($task_data['status'] == 'in-progress' || $task_data['status'] == 'pending')
@if($task_data['due_date'])
<div class="task__contents__due-date__due">Due {{ $task_data['due_date'] }}</div>
@else
<div class="task__contents__due-date__due-not-set">Due date not set.</div>
@endif
@endif
</div>
<div class="task__contents__status-label">
@switch($task_data['status'])
@case('pending')
<div class="task__contents__status-label--pending">
Pending
</div>
@break
@case('in-progress')
<div class="task__contents__status-label--in-progress">
In Progress
</div>
@break
@case('completed')
<div class="task__contents__status-label--completed">
Completed
</div>
@break
@case('on-hold')
<div class="task__contents__status-label--on-hold">
On Hold
</div>
@break
@case('canceled')
<div class="task__contents__status-label--canceled">
Canceled
</div>
@break
@endswitch
</div>
</div>
</div>
