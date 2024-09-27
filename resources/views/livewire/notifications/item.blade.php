<form wire:submit.prevent="see">
    <button type="submit" class="flex flex-col space-y-2 items-end mb-4 py-1 px-2 text-sm hover:bg-gray-100 text-left {{ $linkType }} {{ $notification->seen == 0 ? 'bg-secondary-50 text-secondary-500' : 'bg-white text-gray-900' }}">
        @switch($notification_type)
            @case('mentioned')
            <span class="flex space-x-2">
                <span>
                    <x-svg.comment class="h-5 w-5 flex-grow"/>
                </span>
                <span class="flex-grow-0">
                    <strong>{{ $notification->data['author']['name'] }}</strong> mentioned you in a {{ $notification->data['model']['class'] }}: <i><strong>{{ Str::limit(str_replace('&nbsp;', ' ', strip_tags($notification->data['comment']['body'])), 60) }}</strong></i>
                </span>
            </span>
            @break
        @endswitch
        <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
    </button>
</form>
