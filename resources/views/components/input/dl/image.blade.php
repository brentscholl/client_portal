@props([
'label' => "",
])

    <div
        wire:key="data_{{ $attributes->whereStartsWith('wire:model')->first() }}"
        x-data="{ isEditing: false }"
        x-init="
            @this.on('notify-saved', () => { isEditing = false });

            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginImageResize);

            FilePond.setOptions({
                allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes['wire:model'] }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes['wire:model'] }}', filename, load)
                    },
                },
            });

            FilePond.create($refs.input, {
                acceptedFileTypes: ['image/png', 'image/jpeg'],
                maxFileSize: '7MB',
                allowImageResize: true,
                imageResizeTargetWidth: '300px',
                imageResizeTargetHeight: '300px',
            });
        "
        class="py-4 items-center sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4"
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
            <span class="flex-grow">
                <img class="h-8 w-8 rounded-full" src="{{ $slot }}" alt="">
            </span>
            <span class="ml-4 flex-shrink-0 flex items-start space-x-4">
                <button
                    type="button"
                    @click="isEditing = true; $nextTick(() => focus())"
                    class="font-medium text-secondary-600 hover:text-secondary-500 transition duration-150 ease-in-out"
                >
                    Update
                </button>
                @if(auth()->user()->avatar)
                    <span class="text-gray-300" aria-hidden="true">|</span>
                    <button
                        type="button"
                        wire:click="removeAvatar"
                        class="font-medium text-secondary-600 hover:text-secondary-500 transition duration-150 ease-in-out"
                    >
                        Remove
                    </button>
                @endif
            </span>
        </dd>

        <!-- Editing -->
        <dd
            wire:ignore
            wire:key="avatar"
            x-show="isEditing" x-cloak
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            class="sm:mt-0 sm:col-span-2"
        >
            <form wire:submit.prevent="saveImage"
                class="flex space-x-4 items-center text-sm leading-5 text-gray-900"
            >
                <span class="flex-grow">
                    <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                        <input
                            {{ $attributes->whereStartsWith('wire:model') }}
                            type="file" x-ref="input">
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
                        wire:loading.attr="disabled"
                    >
                      Save
                    </button>
                </span>
            </form>
        </dd>
    </div>
@section('styles')
    <link href="{{ asset('css/filepond.css') }}" rel="stylesheet">
@endsection
@section('scripts.footer')
    <script src="{{ asset('js/filepond.js') }}"></script>
@endsection

