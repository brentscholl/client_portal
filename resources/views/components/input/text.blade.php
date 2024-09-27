@props([
    'type' => "text",
    'label' => "",
    'required' => false,
    'placeholder' => "",
    'lead' => "",
])
<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}" class="{{ $required ? 'required' : '' }}">{!! $label !!}</label>
    <div class="form-input-container">
        @if($lead)
            <span class="form-input-container__lead">
              {{ $lead }}
            </span>
        @endif
        <input
            onkeydown="return event.key != 'Enter';"
            {{ $attributes->whereStartsWith('wire:model') }}
            @if($attributes->whereStartsWith('wire:model')->first())
{{--            id="{{ $attributes->whereStartsWith('wire:model')->first() }}"--}}
            @endif
            id="{{ $attributes->whereStartsWith('wire:model')->first() }}"
            type="{{ $type }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            @error($attributes->whereStartsWith('wire:model')->first())
                class="form-input-container__input form-input-container__input--has-error @if($lead) form-input-container__input--has-lead @endif"
                aria-invalid="true"
                aria-describedby="{{ $attributes->whereStartsWith('wire:model')->first() }}-error"
            @else
                class="form-input-container__input @if($lead) form-input-container__input--has-lead @endif"
            @endif
        >
        @error($attributes->whereStartsWith('wire:model')->first())
            <div wire:key="error_svg_{{ $attributes->whereStartsWith('wire:model')->first() }}"
                class="form-input-container__input__icon--has-error">
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

