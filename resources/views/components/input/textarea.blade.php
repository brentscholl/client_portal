@props([
    'label' => "",
    'required' => false,
    'placeholder' => "",
    'uid' => rand(),
])
<div class="{{ $attributes->get('class') }}">
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}" class="{{ $required ? 'required' : '' }}">{{ $label }}</label>
    <div class="form-input-container">
        <textarea rows="3"
            {{ $attributes->whereStartsWith('wire:model') }}
            @if($attributes->whereStartsWith('wire:model')->first())
            id="{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}"
            @endif
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            @error($attributes->whereStartsWith('wire:model')->first())
                class="form-input-container__input form-input-container__input--has-error"
                aria-invalid="true"
                aria-describedby="{{ $attributes->whereStartsWith('wire:model')->first() }}-error"
            @else
                class="form-input-container__input"
            @endif
        ></textarea>
        @error($attributes->whereStartsWith('wire:model')->first())
            <div wire:key="error_svg_{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}"
                class="form-input-container__input__icon--has-error">
                <x-svg.error />
            </div>
        @enderror
    </div>
    @error($attributes->whereStartsWith('wire:model')->first())
        <p wire:key="error_{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}"
            class="form-input-container__input__error-message"
            id="error-{{ $attributes->whereStartsWith('wire:model')->first() }}-{{ $uid }}">
            {{ $message }}
        </p>
    @enderror
</div>
