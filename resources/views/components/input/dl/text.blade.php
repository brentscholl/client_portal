@props([
'type' => "text",
'label' => "",
'required' => false,
'requiredLabel' => true,
'autofocus' => false,
'placeholder' => "",
'action' => 'Update'
])
<div
    wire:key="data_{{ $attributes->whereStartsWith('wire:model')->first() }}"
    x-data="{
        isEditing: false,
        focus: function() {
            const textInput = this.$refs.textInput;
            textInput.focus();
            textInput.select();
        }
    }"
    x-init="@this.on('notify-saved', () => { isEditing = false })"
    class="py-4 items-center sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 {{ $attributes->get('class') }}"
>
    <dt class="text-sm leading-5 font-medium text-gray-500">
        <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}">
            {{ $label }}
        </label>
    </dt>

    <!-- Not Editing -->
    <dd x-show="!isEditing"
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2"
    >
        <span class="flex-grow">{{ $slot }}</span>
        <span class="flex-shrink-0">
            <button
                type="button"
                @click="isEditing = true; $nextTick(() => focus())"
                class="font-medium text-secondary-600 hover:text-secondary-500 transition duration-150 ease-in-out"
            >
                {{ $action }}
            </button>
        </span>
    </dd>

    <!-- Editing -->
    <dd x-show="isEditing" x-cloak
        x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
        class="sm:mt-0 sm:col-span-2"
    >
        <form wire:submit.prevent="save"
            class="flex space-x-4 items-center text-sm leading-5 text-gray-900"
        >
            <span class="flex-grow">
                <div class="max-w-lg relative rounded-md shadow-sm sm:max-w-xs">
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
                            class="form-input border border-red-500 block w-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent sm:text-sm sm:leading-5"
                            aria-invalid="true"
                            aria-describedby="error"
                        @else
                            class="form-input block w-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent sm:text-sm sm:leading-5"
                        @endif
                        x-ref="textInput"
                    >
                @error($attributes->whereStartsWith('wire:model')->first())
                    <div wire:key="error_svg{{ $attributes->whereStartsWith('wire:model')->first() }}"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <!-- Heroicon name: exclamation-circle -->
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"/>
                        </svg>
                    </div>
                @enderror
                </div>
                @error($attributes->whereStartsWith('wire:model')->first())
                    <p wire:key="error_{{ $attributes->whereStartsWith('wire:model')->first() }}" class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                @enderror
            </span>
            <span class="flex-shrink-0 flex items-start space-x-4">
                <button
                    type="button"
                    wire:click="cancel"
                    @click="isEditing = false"
                    class="font-medium text-red-600 hover:text-red-500 transition duration-150 ease-in-out"
                >
                  Cancel
                </button>
                <span class="text-gray-300" aria-hidden="true">|</span>
                <button
                    type="submit"
                    class="font-medium text-green-400 hover:text-green-500 transition duration-150 ease-in-out"
                >
                  Save
                </button>
            </span>
        </form>
    </dd>
</div>
