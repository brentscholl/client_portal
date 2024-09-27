@props([
    'triggerText' => '',
    'triggerClass' => '',
    'type' => 'delete',
    'modalTitle' => '',
    'submitText' => '',
    'cancelText' => 'Cancel',
    'showSubmitBtn' => true,
    'load' => false,
    'size' => 'default',
    'customTrigger' => '',
])
<div x-data="{ open: false }"
    @keydown.window.escape="open = false"
    x-ref="dialog"
>
    @if($customTrigger)
        {!! $customTrigger !!}
    @else
        <button type="button" @if($load) wire:click="load" @endif @click="open = true" class="{{ $triggerClass }}" role="menuitem">
            {{ $triggerText }}
        </button>
    @endif
    <div x-cloak x-show="open" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-description="Background overlay, show/hide based on modal state."
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-5"
                @click="open = false"
                aria-hidden="true"
            >
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>

            <?php
            switch ( $size ) {
                case('default'):
                    $size_class = "sm:max-w-lg";
                    break;
                case('md'):
                    $size_class = "sm:max-w-2xl";
                    break;
                case('lg'):
                    $size_class = "sm:max-w-4xl";
                    break;
                default:
                    $size_class = "sm:max-w-lg";
                    break;

            }
            ?>
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-description="Modal panel, show/hide based on modal state."
                class="-translate-y-8 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $size_class }} sm:w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        @switch($type)
                            @case('delete')
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-500" x-description="Heroicon name: outline/exclamation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            @break
                            @case('add')
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-secondary-100 sm:mx-0 sm:h-10 sm:w-10">
                                <x-svg.plus-circle class="h-6 w-6 text-secondary-500"/>
                            </div>
                            @break
                            @case('send')
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-secondary-100 sm:mx-0 sm:h-10 sm:w-10">
                                <x-svg.send class="h-4 w-4 text-secondary-500"/>
                            </div>
                            @break
                            @case('cancel')
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <x-svg.cancel class="h-4 w-4 text-red-500"/>
                            </div>
                            @break
                        @endswitch
                        <div class="mt-3 flex-grow text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {!! $modalTitle !!}
                            </h3>
                            <div
                                class="mt-2 overflow-y-auto max-h-96">
                                <p class="text-sm text-gray-500">
                                    {{ $slot }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    @if($showSubmitBtn)
                        @switch($type)
                            @case('delete')
                            @case('cancel')
                            <button
                                {{ $attributes->whereStartsWith('wire:click') }}
                                type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="open = false">
                                {{ $submitText ? $submitText : 'Delete' }}
                            </button>
                            @break
                            @case('add')
                            <button
                                {{ $attributes->whereStartsWith('wire:click') }}
                                type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary-600 text-base font-medium text-white hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="open = false">
                                {{ $submitText ? $submitText : 'Add' }}
                            </button>
                            @break
                            @case('send')
                            <button
                                {{ $attributes->whereStartsWith('wire:click') }}
                                type="button"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary-600 text-base font-medium text-white hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="open = false">
                                {{ $submitText ? $submitText : 'Send' }}
                            </button>
                            @break
                        @endswitch
                    @endif
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="open = false">
                        {{ $cancelText }}
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
