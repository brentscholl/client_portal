@props([
    'title' => '',
    'subtitle' => '',
    'cancelBtn' => 'Cancel',
    'saveBtn' => 'Save',
    'showButtons' => true,
])
<div>
    <div x-cloak x-show="open"
        class="z-50 fixed backdrop-blur-2 inset-0 overflow-hidden"
        x-transition:enter="transition ease-in-out duration-500 sm:duration-700"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in-out duration-500 sm:duration-700"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="background: rgba(0,0,0,0.07);"
    ></div>
    <div x-cloak x-show="open"
        x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="z-50 fixed inset-0 overflow-hidden"
    >

        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute w-full h-full"
                @click="open = false;"
            ><!-- Invisible slideout close --></div>
            <section class="absolute inset-y-0 pl-16 max-w-full right-0 flex" aria-labelledby="slide-over-heading">
                <div class="w-screen max-w-xl" x-description="Slide-over panel, show/hide based on slide-over state.">
                    <div class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl">
                        <div class="flex-1 h-0 overflow-y-auto">
                            <div class="py-6 px-4 gradient sm:px-6">
                                <div class="flex items-center justify-between">
                                    <h2 id="slide-over-heading" class="text-lg font-medium text-white">
                                        {{ $title }}
                                    </h2>
                                    <div class="ml-3 h-7 flex items-center">
                                        <button type="button" @click="open = false;" class="bg-transparent rounded-md text-secondary-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                                            <span class="sr-only">Close panel</span>
                                            <svg class="h-6 w-6" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @if($subtitle)
                                <div class="mt-1">
                                    <p class="text-sm text-secondary-300">
                                        {{ $subtitle }}
                                    </p>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 flex flex-col justify-between">
                                <div class="px-4 py-4 divide-y divide-gray-200 sm:px-6">
                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                        @if($showButtons)
                            <div class="flex-shrink-0 px-4 py-4 flex justify-end space-x-2">
                                <x-button @click="open = false;" btn="cancel">
                                    {!! $cancelBtn !!}
                                </x-button>
                                <x-button type="submit" :loader="true">
                                    {!! $saveBtn !!}
                                </x-button>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

