@props([
'label' => '',
'name' => '',
'toggled' => true
])
<div wire:key="radio_{{ $attributes->whereStartsWith('wire:model')->first() }}"
    class="py-4 items-center sm:py-5 sm:grid sm:grid-cols-2 {{ $attributes->get('class') }}">
    <dt class="text-sm leading-5 font-medium text-gray-500">
        {{ $label }}
    </dt>
    <dd class="flex text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-1">
            <button
                type="button"
                {{ $attributes->whereStartsWith('wire:model') }}
                wire:click="setState('{{ $attributes->whereStartsWith('wire:model')->first() }}')"
                @if($toggled == 'true')
                    class="bg-secondary-600 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:ml-auto"
                @else
                    class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:ml-auto"
                @endif
            >
                <span class="sr-only">{{ $label }}</span>
                <span aria-hidden="true"
                    @if($toggled == 'true')
                        class="translate-x-5 relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                    @else
                        class="translate-x-0 relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                    @endif
                >
                    @if($toggled == 'true')
                    <span class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity opacity-100 ease-out duration-100">
                        <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">
                          <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"></path>
                        </svg>
                    </span>
                    @else
                    <span class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity opacity-100 ease-in duration-200">
                        <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                          <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                    @endif
                </span>
            </button>
    </dd>
</div>
