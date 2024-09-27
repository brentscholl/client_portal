<div class="tutorial">
<div class="tutorial__inner">
<!---- Tutorial Top Container ---->
<div class="tutorial__content">
<div class="tutorial__title">{{ $tutorial_data['title'] }}</div>
<div>{{ $tutorial_data['body'] }}</div>
</div>

<div class="tutorial__play">
<a target="_blank" href="{{ $tutorial_data['video_url'] }}"
class="tutorial__play__btn"
>
<svg xmlns="http://www.w3.org/2000/svg" class="tutorial__play__svg" viewBox="0 0 20 20" fill="currentColor">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
</svg>
</a>
</div>
</div>
</div>
