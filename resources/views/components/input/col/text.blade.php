@props([
'type' => "text",
'label' => "",
'required' => false,
'requiredLabel' => true,
'autofocus' => false,
'placeholder' => "",
'lead' => "",
])
<div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5 {{ $attributes->get('class') }}">
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}"
        class="sm:mt-px sm:pt-2">
        {{ $label }}
    </label>
    <div class="mt-1 sm:mt-0 sm:col-span-2">
        <div class="max-w-lg flex relative rounded-md shadow-sm sm:max-w-xs">
            @if($lead)
                <span class="form-input-container__lead">
                  {{ $lead }}
                </span>
            @endif
            <input
                {{ $attributes->whereStartsWith('wire:model') }}
                @if($attributes->whereStartsWith('wire:model')->first())
                id="{{ $attributes->whereStartsWith('wire:model')->first() }}"
                @endif
                type="{{ $type }}"
                @if($required) required @endif
                autofocus="{{ $autofocus }}"
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
</div>
