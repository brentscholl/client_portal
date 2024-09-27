<div
    x-cloak x-show="openCard"
    x-transition:enter="transition-all duration-700 ease-in-out"
    x-transition:enter-start="max-h-0 opacity-0"
    x-transition:enter-end="max-h-full opacity-100"
    x-transition:leave="overflow-hidden transition-all duration-300 ease-in-out"
    x-transition:leave-start="max-h-full opacity-100"
    x-transition:leave-end="max-h-0 opacity-0"
    class="pt-6"
>

    <div class="max-w-7xl mx-auto">
        {{ $slot }}
    </div>
</div>
