@props([
    'label' => '',
    'description' => null,
    'value' => '',
    'type' => 'checkbox',
    'uid' => rand(),
])
<div class="relative flex items-start">
    <div class="flex items-center h-5">
        <input
            {{ $attributes->whereStartsWith('wire:model') }}
            {{ $attributes->whereStartsWith('x') }}
            name="{{ $attributes->whereStartsWith('wire:model')->first() }}"
            value="{{ $value }}"
            id="checkbox-{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}"
            aria-describedby="checkbox-{{ $attributes->whereStartsWith('wire:model')->first() }}-description"
            type="{{ $type }}"
            class="focus:ring-secondary-500 h-4 w-4 text-secondary-600 border border-gray-300 rounded">
    </div>
    <div class="ml-3 text-sm">
        <label for="checkbox-{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}" class="font-medium text-gray-700">{{ $label }}</label>
        @if($description)
            <p id="checkbox-{{ $attributes->whereStartsWith('wire:model')->first() }}-description" class="text-gray-500">{{ $description }}</p>
        @endif
    </div>
</div>
