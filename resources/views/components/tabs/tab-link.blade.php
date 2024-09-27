@props([
    'tab',
    'tabLink',
    'tabLabel',
    'tabSvg',
    'count' => 0,
])
<button type="button" wire:click="showTab('{{ $tabLink }}')" class="@if($tab == $tabLink)border-secondary-500 text-secondary-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-400 @endif whitespace-nowrap flex items-center space-x-2 py-4 px-1 border-b-2 font-medium text-sm" >
    <x-dynamic-component :component="'svg.' . $tabSvg" class="h-4 w-4" />
    <span>{{ $tabLabel }}</span>
    @if($count > 0)
        <span class="@if($tab == $tabLink) bg-secondary-100 text-secondary-600 @else bg-gray-100 text-gray-900 @endif hidden py-0.5 px-2.5 rounded-full text-xs font-medium md:inline-block">
            {{ $count }}
        </span>
    @endif
</button>
