<div class="project project--{{ $project_data['status'] }}">
    <div class="project__title">
        <div class="project__title__inner">
            <div class="project__title__service">
<span class="project__title__service__container">
<x-dynamic-component :component="'svg.service.'.$project_data['service_slug']" class="project__title__service__svg"/>
<span>{{ $project_data['service_title'] }}</span>
</span>
            </div>
            <div class="project__title__inner__title">
                {{ $project_data['title'] }}
            </div>
        </div>
    </div>
    <div>
        <div class="project__phase-title">Phases:</div>
        <!-- This example requires Tailwind CSS v2.0+ -->
        <nav aria-label="Progress">
            <ol class="project__phases">
                @foreach($project_data['phases'] as $phase)
                    <li class="project__phase {{ $loop->last ? '' : 'project__phase--last' }}">
                        @switch($phase['status'])
                            @case('completed')
                            <div class="project__phase__divide-line" aria-hidden="true">
                                <div class="project__phase__divide-line__line line--completed"></div>
                            </div>
                            <div class="project__phase__circle--completed">
                                <!-- Heroicon name: solid/check -->
                                <svg class="project__phase__circle__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="project__phase__circle__title--completed">{{ $phase['title'] }}</span>
                            </div>
                            @break
                            @case('in-progress')
                            <div class="project__phase__divide-line" aria-hidden="true">
                                <div class="project__phase__divide-line__line line--in-progress"></div>
                            </div>
                            <div class="project__phase__circle--in-progress">
                                <span class="project__phase__circle__step--in-progress">{{ $phase['step'] }}</span>
                                <span class="project__phase__circle__title--in-progress">{{ $phase['title'] }}</span>
                            </div>
                            @break
                            @case('pending')
                            <div class="project__phase__divide-line" aria-hidden="true">
                                <div class="project__phase__divide-line__line line--pending"></div>
                            </div>
                            <div class="project__phase__circle--pending">
                                <span class="project__phase__circle__step--pending">{{ $phase['step'] }}</span>
                                <span class="project__phase__circle__title--pending">{{ $phase['title'] }}</span>
                            </div>
                            @break
                        @endswitch
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
    <div class="project__status">
        @if($project_data['status'] == 'completed')
            <div class="project__status__completed-on">Completed
                on {{ $project_data['completed_at'] }}</div>
        @elseif($project_data['status'] == 'in-progress' || $project_data['status'] == 'pending')
            @if($project_data['due_date'])
                <div class="project__status__due">Due {{ $project_data['due_date'] }}</div>
            @else
                <div class="project__status__due-not-set">Due date not set.</div>
            @endif
        @endif
        <div class="project__status__container">
            @switch($project_data['status'])
                @case('pending')
                <div class="project__status__label project__status__label--pending">
                    Pending
                </div>
                @break
                @case('in-progress')
                <div class="project__status__label project__status__label--in-progress">
                    In Progress
                </div>
                @break
                @case('completed')
                <div class="project__status__label project__status__label--completed">
                    Completed
                </div>
                @break
                @case('on-hold')
                <div class="project__status__label project__status__label--on-hold">
                    On Hold
                </div>
                @break
                @case('canceled')
                <div class="project__status__label project__status__label--canceled">
                    Canceled
                </div>
                @break
            @endswitch
        </div>
    </div>
</div>
