@props([
    'inline' => false,
])
<div x-data="{ menuOpen: false }" @close-slideout.window="menuOpen = false" @keydown.escape="menuOpen = false" @click.away="menuOpen = false"
    class="{{ $inline ? 'relative' : 'absolute top-1 right-0' }} inline-block text-left">
    <div>
        <button
            wire:click="showMenuContent"
            @click="menuOpen = !menuOpen" class="bg-white rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-secondary-500" id="options-menu" aria-haspopup="true" x-bind:aria-expanded="menuOpen" aria-expanded="true">
            <span class="sr-only">Open options</span>
            <svg class="h-5 w-5" x-description="Heroicon name: dots-vertical" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
            </svg>
        </button>
    </div>

    <div x-cloak x-show="menuOpen"
        x-description="Dropdown panel, show/hide based on dropdown state."
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="z-10 origin-top-right absolute right-5 top-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
    >
        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            {{ $slot }}
        </div>
    </div>
</div>
