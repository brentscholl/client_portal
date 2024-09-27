@props([
    'type' => "text",
    'label' => "",
    'required' => false,
    'placeholder' => "",
    'uid' => rand(),
    'minDate' => "",
])
<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}">{{ $label }}</label>
    <div class="form-input-container">
        <div class="form-input-container__lead">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
            </svg>
        </div>
        <input
            x-data
            x-ref="input"
            x-init="new Pikaday({ field: $refs.input, format: 'MMMM DD YYYY', minDate: new Date('{{ $minDate}}'),})"
            onkeydown="return event.key != 'Enter';"
            {{ $attributes->whereStartsWith('wire:model') }}
            id="{{ $attributes->whereStartsWith('wire:model')->first() }}"
            type="{{ $type }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            @error($attributes->whereStartsWith('wire:model')->first())
                class="form-input-container__input form-input-container__input--has-lead form-input-container__input--has-error"
                aria-invalid="true"
                aria-describedby="{{ $attributes->whereStartsWith('wire:model')->first() }}-error"
            @else
                class="form-input-container__input form-input-container__input--has-lead"
            @endif
        >
        @error($attributes->whereStartsWith('wire:model')->first())
            <div wire:key="error_svg_{{ $attributes->whereStartsWith('wire:model')->first() }}"
                class="form-input-container__input__icon--has-error">
                <!-- Heroicon name: exclamation-circle -->
                <x-svg.error />
            </div>
        @enderror
    </div>
    @error($attributes->whereStartsWith('wire:model')->first())
        <p wire:key="error_{{ $attributes->whereStartsWith('wire:model')->first() }}"
            class="form-input-container__input__error-message"
            id="error-{{ $attributes->whereStartsWith('wire:model')->first() }}">
            {{ $message }}
        </p>
    @enderror
</div>
