<div tooltip="Change Status" x-data="{ menuOpen: false }" @keydown.escape="menuOpen = false" @click.away="menuOpen = false" class="relative flex-none {{ $class }}">
    @php($sizeClasses = $large ? 'text-sm px-4 py-1 shadow-sm' : 'text-xs px-2 py-0.5')
    @switch($model->status)
        @case('pending')
        <button @click="menuOpen = !menuOpen"
            class="{{ $sizeClasses }} flex-shrink-0 inline-block focus:outline-none text-gray-800 font-medium bg-gray-200 rounded-full">
            Pending
        </button>
        @break
        @case('in-progress')
        <button @click="menuOpen = !menuOpen"
            class="{{ $sizeClasses }} flex-shrink-0 inline-block focus:outline-none text-secondary-800 font-medium bg-secondary-100 rounded-full">
            In Progress
        </button>
        @break
        @case('completed')
        <button @click="menuOpen = !menuOpen"
            class="{{ $sizeClasses }} flex-shrink-0 inline-block focus:outline-none text-green-800 font-medium bg-green-100 rounded-full">
            Completed
        </button>
        @break
        @case('on-hold')
        <button @click="menuOpen = !menuOpen"
            class="{{ $sizeClasses }} flex-shrink-0 inline-block focus:outline-none text-amber-800 font-medium bg-amber-100 rounded-full">
            On Hold
        </button>
        @break
        @case('canceled')
        <button @click="menuOpen = !menuOpen"
            class="{{ $sizeClasses }} flex-shrink-0 inline-block focus:outline-none text-red-800 font-medium bg-red-100 rounded-full">
            Canceled
        </button>
        @break
    @endswitch
    <div x-cloak x-show="menuOpen"
        x-description="Dropdown panel, show/hide based on dropdown state."
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute right-5 top-6 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
    >
        <div class="flex flex-col space-y-2 py-2 px-2" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
            <p class="text-center font-medium text-xs">Update Status</p>
            <button @click="menuOpen = !menuOpen" wire:click="updateStatus('pending')"
                class="flex-shrink-0 inline-block px-2 py-2 focus:outline-none text-gray-800 text-xs font-medium bg-gray-100 rounded-full hover:text-white hover:bg-gray-500">
                Pending
            </button>
            <button @click="menuOpen = !menuOpen" wire:click="updateStatus('in-progress')"
                class="flex-shrink-0 inline-block px-2 py-2 focus:outline-none text-secondary-800 text-xs font-medium bg-secondary-100 rounded-full hover:text-white hover:bg-secondary-500">
                In Progress
            </button>
            <button @click="menuOpen = !menuOpen" wire:click="updateStatus('completed')"
                class="flex-shrink-0 inline-block px-2 py-2 focus:outline-none text-green-800 text-xs font-medium bg-green-100 rounded-full hover:text-white hover:bg-green-500">
                Completed
            </button>
            <button @click="menuOpen = !menuOpen" wire:click="updateStatus('on-hold')"
                class="flex-shrink-0 inline-block px-2 py-2 focus:outline-none text-amber-800 text-xs font-medium bg-amber-100 rounded-full hover:text-white hover:bg-amber-500">
                On Hold
            </button>
            <button @click="menuOpen = !menuOpen" wire:click="updateStatus('canceled')"
                class="flex-shrink-0 inline-block px-2 py-2 focus:outline-none text-red-800 text-xs font-medium bg-red-100 rounded-full hover:text-white hover:bg-red-500">
                Canceled
            </button>
        </div>
    </div>
</div>
