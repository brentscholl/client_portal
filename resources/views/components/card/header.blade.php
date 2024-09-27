@props([
    'title',
    'count' => '',
    'doesNotHaveCount' => false
])
<div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6 pb-5">
    <div class="flex space-x-2">
        <h2 id="activity-title" class="text-lg font-medium {{ $count > 0 || $doesNotHaveCount ? 'text-gray-900' : 'text-gray-500' }} hover:text-gray-600 transition duration-100 cursor-pointer"
            @click="openCard = !openCard"
            wire:click="openCard">{{ $title }}</h2>
        @if($count)
        <span class="shadow-sm inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $count }}</span>
        @endif
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
