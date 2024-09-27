@props([
'message' => 'Successfully saved!'
])
<div
    x-data="{ open: false }"
    x-init="
            window.addEventListener('load', (event) => {
                setTimeout(() => { open = true }, 300)
                setTimeout(() => { open = false }, 2800);
            })
        "
    x-show="open"
    x-description="Notification panel, show/hide based on alert state."
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="z-50 max-w-sm w-full fixed top-6 right-6 bg-white shadow-lg rounded-lg pointer-events-auto">
    <div class="rounded-lg shadow-xs overflow-hidden">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-400" x-description="Heroicon name: check-circle" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm leading-5 font-medium text-gray-900">
                        {{ $message }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
