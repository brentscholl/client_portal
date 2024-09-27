@props([
    'label' => "",
    'required' => false,
    'lead' => "",
])
<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}">{{ $label }}</label>
    <div class="form-input-container">
        @if($lead)
            <span class="form-input-container__lead">
              {{ $lead }}
            </span>
        @endif
        <select
            onkeydown="return event.key != 'Enter';"
            {{ $attributes->whereStartsWith('wire:model') }}
            id="{{ $attributes->whereStartsWith('wire:model')->first() }}"
            @if($required) required @endif
            @error($attributes->whereStartsWith('wire:model')->first())
                class="form-input-container__input form-input-container__input--has-error @if($lead) form-input-container__input--has-lead @endif"
                aria-invalid="true"
                aria-describedby="{{ $attributes->whereStartsWith('wire:model')->first() }}-error"
            @else
                class="form-input-container__input @if($lead) form-input-container__input--has-lead @endif"
            @endif
        >
            {{ $slot }}
        </select>
    </div>
    @error($attributes->whereStartsWith('wire:model')->first())
        <p wire:key="error_{{ $attributes->whereStartsWith('wire:model')->first() }}"
            class="form-input-container__input__error-message"
            id="error-{{ $attributes->whereStartsWith('wire:model')->first() }}">
            {{ $message }}
        </p>
    @enderror
</div>
