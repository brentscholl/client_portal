@props([
    'opened' => false,
])
<div x-data="{ openCard: @entangle('cardOpened') }" class="relative bg-white rounded shadow-md px-5 pt-5 pb-7 transition-all duration-500 ease-in-out" style="height: auto;">
    <div class="divide-y divide-gray-200">
        {{ $slot }}
    </div>
    <button
        type="button"
        @click="openCard = !openCard"
        wire:click="openCard"
        style="bottom: -1.25rem; left: 50%; transform: translateX(-50%)"
        class="rounded-full absolute shadow w-10 h-10 text-center flex items-center justify-center bg-white border border-gray-300 text-gray-300 hover:bg-gray-100 hover:text-gray-400 transition duration-100 focus:outline-none"
    >
        <svg
            x-bind:style="openCard == true ? 'transform: rotate(180deg);' : ''"
            xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition duration-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
</div>
