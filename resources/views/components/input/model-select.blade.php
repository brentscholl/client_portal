@props([
    'models' => null,
    'model' => '',
    'modelType' => null,
    'selectLabel' => null,
    'modelLabelProperty' => 'title',
    'label' => null,
    'required' => false,
    'uid' => rand(),
])
<div x-data="{ open: false, select: null}" x-init="">
    @if($label)
        <label id="{{ $attributes->get('id') }}-{{ $uid }}" class="{{ $required ? 'required' : '' }}">
            {{ $label }}
        </label>
    @endif
    <div class="mt-1 relative">
        <button
            @click="open = true"
            type="button"
            class="bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left focus:outline-none focus:ring-1 focus:ring-secondary-500 focus:border-secondary-500 sm:text-sm"
        >
            @if($model)
                <span class="block truncate cursor-pointer">{{ $model->$modelLabelProperty }}</span>
            @else
                <span class="block truncate cursor-pointer">{{ $selectLabel }}</span>
            @endif
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: selector" xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </span>
        </button>

        <div x-show="open"
            @click.away="open = false"
            x-description="Select popover, show/hide based on select state."
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute z-50 mt-1 w-full rounded-md bg-white shadow-lg"
        >
            <div
                x-cloak
                class="max-h-50 rounded-md border-gray-200 border text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
            >

                @if($models)
                    @foreach($models as $m)
                        <button
                            wire:key="{{ $loop->index }}-{{ $uid }}"
                            type="button"
                            wire:click='set{{ $modelType }}("{{ $m->id }}")'
                            @click="open = false"
                            @if(optional($model)->id == $m->id)
                            class="w-full relative inline-flex justify-start py-2 px-4 border border-transparent text-primary-500 bg-white shadow-sm text-sm font-medium rounded-md hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                            @else
                            class="w-full relative inline-flex justify-start py-2 px-4 border border-transparent bg-white shadow-sm text-sm font-medium rounded-md hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                            @endif
                        >
                        <span
                            @if(optional($model)->id == $m->id)
                            class="font-semibold block truncate"
                            @else
                            class="font-normal block truncate"
                            @endif
                        >
                            {{ Str::limit($m->$modelLabelProperty, 50) }}
                        </span>

                            @if(optional($model)->id == $m->id)
                                <span
                                    x-description="Checkmark, only display for selected option."
                                    class="text-white absolute inset-y-0 right-0 flex items-center pr-4 text-secondary-600"
                                >
                                <svg class="h-5 w-5" x-description="Heroicon name: check" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            @endif
                        </button>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
