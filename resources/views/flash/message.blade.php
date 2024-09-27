<div>
    @if($shown)
        <div x-data="{ open: false }"
            x-init="
                setTimeout(() => { open = true }, 300)
                setTimeout(() => { open = false }, 2800);
            "
            x-show="open"
            x-description="Notification panel, show/hide based on alert state."
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="z-50 max-w-sm w-full fixed top-6 right-6 {{ $styles['bg-color'] }} border {{ $styles['border-color'] }} shadow-lg rounded-lg pointer-events-auto">
            <div class="rounded-lg shadow-xs overflow-hidden">
                <div class="p-4">
                    <div class="flex items-start">
                        @if ($styles['icon'] ?? false)
                            <div class="flex-shrink-0">
                                <p class="{{ $styles['icon-color'] }}">
                                    <i class="{{ $styles['icon'] }}"></i>
                                </p>
                            </div>
                        @endif
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm leading-5 font-medium {{ $styles['text-color'] }}">
                                {!! $message['message'] !!}
                            </p>
                        </div>
                        @if ($message['dismissable'] ?? false)
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5">
                                    <button class="inline-flex rounded-md p-1.5 {{ $styles['text-color'] }} focus:outline-none transition ease-in-out duration-150" wire:click="dismiss">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
