<div class="question {{ $question_data['has_answer'] ? 'question--has-answer' : '' }} ">
<div class="question__inner">
<!---- Question Type ---->
<div class="question__type">
<div class="question__type__body">
{{ $question_data['body'] }}
</div>
@if($question_data['tagline'])
<p class="question__tagline">
<x-svg.info-circle class="question__tagline__svg"/>
<span>{{ $question_data['tagline'] }}</span>
</p>
@endif
</div>

<!---- Question Choices ---->
@if($question_data['type'] != 'detail')
<div class="question__choices">
<div class="question__choices__title">
<svg xmlns="http://www.w3.org/2000/svg" class="question__choices__title__svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
</svg>
<p class="question__choices__title__label">
Choices:
</p>
</div>
<ul class="question__choices__container">
@switch($question_data['type'])
@case('multi_choice')
@case('select')
@foreach(json_decode($question_data['choices']) as $choice)
<li class="question__choices__choice">{{ $choice }}</li>
@endforeach
@break
@case('boolean')
<li class="question__choices__choice">Yes</li>
<li class="question__choices__choice">No</li>
@break
@endswitch
</ul>
</div>
@endif

<!---- Question Answer ---->
@if( $question_data['has_answer'] )
<div class="question__answer">
<svg xmlns="http://www.w3.org/2000/svg" class="question__answer__svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
<span class="question__answer__label">
Answer:
</span>
</div>
<div class="question__answer__container">
<div class="question__answer__container__inner">
@if($question_data['type'] == 'detail')
@if($question_data['answer']['body'])
<div class="question__answer__body">{{ $question_data['answer']['body'] }}</div>
@endif
@else
<div class="question__answer__choices">
@foreach(json_decode($question_data['answer']['choices']) as $choice)
<div class="question__answer__choice">{{ $choice }}</div>
@endforeach
</div>
@endif
@if(($question_data['answer']['choices'] && $question_data['add_file_uploader']) || ($question_data['answer']['body'] && $question_data['add_file_uploader']))
<hr class="question__answer__divide"/>
@endif
@if($question_data['add_file_uploader'])
@if($question_data['answer']['files'])
<div class="question__files">
@foreach($question_data['answer']['files'] as $file)
<div class="question__file">
<span class="question__file_label">
<x-svg.file class="question__file_svg"/>
<span>{{ $file['src'] }}</span>
</span>
</div>
@endforeach
</div>
@endif
@endif
</div>
{{--                    <p class="text-xs">Answered by:--}}
{{--                        <a href="{{ route('admin.users.show', $answer->user->id) }}" class="text-gray-600 hover:text-secondary-500 transition-all duration-100 ease-in-out">{{ $answer->user->fullname }}</a>--}}
{{--                        <span class="text-gray-400">{{ $answer->created_at->diffForHumans() }}</span></p>--}}
</div>
@endif
</div>
</div>
