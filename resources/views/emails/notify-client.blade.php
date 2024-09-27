@component('mail::message')
<!-- layout start -->
@if($layout)
@foreach($layout as $key => $item)
<!-- Text -->
@if($item['layout'] == 'message')
<div class="message">
{!! nl2br($item['inputs']['message']) !!}
</div>
@endif

<!-- Task Block -->
@if($item['layout'] == 'tasks')
<div class="item-box">
<h3>Tasks</h3>
<div class="item-box__container">
@foreach($data['data'][$key] as $task_data)
@include('emails.components.task', [$task_data])
@endforeach
</div>
</div>
@endif

<!-- Project Block -->
@if($item['layout'] == 'projects')
<div class="item-box">
<h3>Projects</h3>
<div class="item-box__container">
@foreach($data['data'][$key] as $project_data)
@include('emails.components.project', [$project_data])
@endforeach
</div>
</div>
@endif

<!-- Question Block -->
@if($item['layout'] == 'questions')
<div class="item-box">
<h3>Questionnaire</h3>
<div class="item-box__container">
@foreach($data['data'][$key] as $question_data)
@include('emails.components.question', [$question_data])
@endforeach
</div>
</div>
@endif

<!-- Tutorials Block -->
@if($item['layout'] == 'tutorials')
<div class="item-box">
<h3>Tutorials</h3>
<div class="item-box__container">
@foreach($data['data'][$key] as $tutorial_data)
@include('emails.components.tutorial', [$tutorial_data])
@endforeach
</div>
</div>
@endif

<!-- Task Block -->
@if($item['layout'] == 'single_task')
<div class="item-box">
<h3>Task</h3>
<div class="item-box__container">
@if(isset($data['data'][$key]))
@include('emails.components.task', ['task_data' => $data['data'][$key]])
@endif
</div>
</div>
@endif

<!-- Project Block -->
@if($item['layout'] == 'single_project')
<div class="item-box">
<h3>Project</h3>
<div class="item-box__container">
@if(isset($data['data'][$key]))
@include('emails.components.project', ['project_data' => $data['data'][$key]])
@endif
</div>
</div>
@endif

<!-- Question Block -->
@if($item['layout'] == 'single_question')
<div class="item-box">
<h3>Question</h3>
<div class="item-box__container">
@if(isset($data['data'][$key]))
@include('emails.components.question', ['question_data' => $data['data'][$key]])
@endif
</div>
</div>
@endif

<!-- Question Block -->
@if($item['layout'] == 'single_tutorial')
<div class="item-box">
<h3>Tutorial</h3>
<div class="item-box__container">
@if(isset($data['data'][$key]))
@include('emails.components.tutorial', ['tutorial_data' => $data['data'][$key]])
@endif
</div>
</div>
@endif

<!-- Alert -->
@if($item['layout'] == 'alert')
@component('mail::panel')
    {{ $item['inputs']['message'] }}
@endcomponent
@endif

<!-- Link to dashboard -->
@if($item['layout'] == 'link_to_dashboard')
@component('mail::button', ['url' => route('home'), 'color' => 'primary'])
<x-svg.login class="dashboard-link__svg"/>
<span>Go to your dashboard</span>
@endcomponent
@endif

<!-- layout end -->
@endforeach
@endif


Thanks,<br>
<div class="signature">
@if($data['email_signature'] == 'user')
<img class="signature__user-img" src="{{ $data['user']['avatarUrl'] }}" alt="{{ $data['user']['fullname'] }}">
<div class="signature__user-title">
<div class="signature__name">{{ $data['user']['fullname'] }}</div>
<div class="signature__position">{{ $data['user']['position'] }}</div>
</div>
@else
<img class="signature__company-logo"
src="{{ asset('/assets/STEALTH-logo-black.svg') }}"
alt="Stealth Media">
@endif
</div>

@component('mail::subcopy')
    Sub copy
@endcomponent
@endcomponent
