@props([
    'label' => '',
    'description' => null,
    'first' => false,
    'last' => false,
    'uid' => rand(),
])
<label
    x-radio-group-option=""
    class="@if($first) rounded-tl-md rounded-tr-md @elseif($last) rounded-bl-md rounded-br-md @endif relative border p-4 flex cursor-pointer bg-secondary-50 border-secondary-200"
    x-state:on="Checked"
    x-state:off="Not Checked"
    :class="{ 'bg-secondary-50 border-secondary-200': value === '{{ $attributes->get('value') }}', 'border-gray-200': !(value === '{{ $attributes->get('value') }}') }"
>
    <input
        {{ $attributes->whereStartsWith('wire:model') }}
        id="{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}"
        type="radio"
        x-model="value"
        value="{{ $attributes->get('value') }}"
        class="h-4 w-4 mt-0.5 cursor-pointer text-secondary-600 border border-gray-300 focus:ring-secondary-500"
    >
    <div class="ml-3 flex flex-col">
        <span
            class="block text-sm font-medium text-secondary-900"
            x-state:on="Checked"
            x-state:off="Not Checked"
            :class="{ 'text-secondary-900': value === '{{ $attributes->get('value') }}', 'text-gray-900': !(value === '{{ $attributes->get('value') }}') }"
        >
          {{ $label }}
        </span>
        @if($description)
            <span
                class="block text-xs text-secondary-700"
                x-state:on="Checked"
                x-state:off="Not Checked"
                :class="{ 'text-secondary-700': value === '{{ $attributes->get('value') }}', 'text-gray-500': !(value === '{{ $attributes->get('value') }}') }"
            >
              {{ $description }}
            </span>
        @endif
    </div>
</label>
