@props([
    'type' => 'button',
    'btn' => 'secondary',
    'loader' => false,
])
    <button
        {{ $attributes }}
        @if($loader)
            wire:loading.attr="disabled"
        @endif
        type="{{ $type }}"
        @switch($btn)
            @case('secondary')
                class="{{ $attributes->get('class') }} group inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white gradient-secondary transition-all duration-300 ease-in-out hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
            @break
            @case('danger')
                class="{{ $attributes->get('class') }} group inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 transition-all duration-300 ease-in-out hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            @break
            @case('cancel')
                class="{{ $attributes->get('class') }} group inline-flex justify-center py-2 px-4 border border-red-500 shadow-sm text-sm font-medium rounded-md text-red-500 bg-white transition-all duration-300 ease-in-out hover:bg-red-200 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            @break
            @case('edit')
                class="{{ $attributes->get('class') }} group inline-flex justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900"
            @break
            @case('inline')
                class="{{ $attributes->get('class') }} group inline-flex justify-center text-secondary-600 hover:text-secondary-900"
            @break
            @case('inline-menu')
                class="{{ $attributes->get('class') }} group block w-full text-left px-4 py-2 text-sm text-secondary-600 hover:text-secondary-900"
            @break
            @case('icon')
                class="ml-4 bg-white rounded-full h-8 w-8 flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-secondary-500"
            @break
            @default
                class="{{ $attributes->get('class') }}"
            @break
        @endswitch
        {{ $attributes->whereStartsWith('wire:target') }}
        @if($attributes->get('id'))
        id="{{ $attributes->get('id') }}"
        @endif
    >
    @if($loader)
        <span class="mr-2" wire:loading {{ $attributes->whereStartsWith('wire:target') }}>
            <x-svg.spinner class="-ml-1 mr-3 h-5 w-5 text-white" />
        </span>
    @endif
            {{ $slot }}
</button>
