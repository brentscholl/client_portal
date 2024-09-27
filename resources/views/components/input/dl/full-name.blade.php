@props([
'label' => "",
'required' => false,
'autofocus' => false,
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
        {{ $label }}
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
            <span class="flex-col space-y-4">

                <span class="flex flex-row space-x-4">
                    <span class="flex-grow">
                        <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                            <label for="first_name">First Name</label>
                            <div class="form-input-container">
                                <input
                                    wire:model.lazy="first_name"
                                    required="{{ $required }}"
                                    autofocus="{{ $autofocus }}"
                                    x-ref="textInput"
                                    id="first_name"
                                    @error('first_name')
                                        class="form-input border border-red-500 block w-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent sm:text-sm sm:leading-5"
                                        aria-invalid="true"
                                        aria-describedby="error-first_name"
                                    @else
                                        class="form-input block w-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent sm:text-sm sm:leading-5"
                                    @endif
                                >
                                @error('first_name')
                                    <div wire:key="error_svg{{ 'first_name' }}"
                                        class="form-input-container__input__icon--has-error">
                                        <x-svg.error />
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </span>
                    <span class="flex-grow">
                        <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                            <label for="last_name">Last Name</label>
                            <div class="form-input-container">
                                <input
                                    wire:model.lazy="last_name"
                                    required="{{ $required }}"
                                    id="last_name"
                                    @error('last_name')
                                        class="form-input border border-red-500 block w-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent sm:text-sm sm:leading-5"
                                        aria-invalid="true"
                                        aria-describedby="error-last_name"
                                    @else
                                        class="form-input block w-full transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-600 focus:border-transparent sm:text-sm sm:leading-5"
                                    @endif
                                >
                                @error('last_name')
                                    <div wire:key="error_svg{{ 'last_name' }}"
                                        class="form-input-container__input__icon--has-error">
                                        <x-svg.error />
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </span>
                </span>
                    @error('first_name')
                        <p wire:key="error_{{ 'first_name' }}" class="form-input-container__input__error-message" id="first_name-error">{{ $message }}</p>
                    @enderror
                    @error('last_name')
                        <p wire:key="error_{{ 'last_name' }}" class="form-input-container__input__error-message" id="last_name-error">{{ $message }}</p>
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
