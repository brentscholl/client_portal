<x-slideout.wrapper>
    <button
        wire:ignore.self
        wire:click="openSlideout"
        @click="open = true"
        type="button"
        class="ml-3 relative p-1 text-gray-400 rounded-full hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:shadow-outline focus:text-gray-500"
        aria-label="Notifications"
    >
        <div wire:poll.60000ms>
            @if(count(auth()->user()->unreadNotifications))
                <span class="absolute top-0 block" style="right: 1.25rem;">
                    <span class="animate-ping left-0 absolute rounded-full bg-red-500 w-full h-5"></span>
                    <span class="relative justify-center rounded-full flex items-center px-1.5 bg-red-500 text-center w-auto h-5 text-white font-medium text-xs"
                        style=" min-width: 1.25rem;"
                    >{{ count(auth()->user()->unreadNotifications) }}</span>
                </span>
            @endif
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </div>
    </button>
    <x-slideout
        title="Notifications"
        :showButtons="false"
    >
        @if (!count(auth()->user()->notifications))
            <div class="text-center">
                <small>0 notifications</small>
            </div>
        @endif
        <?php
        $userNotifications = auth()->user()->notifications()->paginate(6);
        ?>
        @foreach ($userNotifications as $n)
            @livewire('notifications.item', ['notification' => $n, 'linkType' => 'dropdown-item'], key($n->id))
        @endforeach

    </x-slideout>
</x-slideout.wrapper>
