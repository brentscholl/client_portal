<div class="relative rounded-lg border border-gray-100 bg-white pr-6 pl-8 py-2 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.resource class="h-4 w-4 text-gray-500"/>
    </div>

    <!-- interior -->
    <div class="flex items-center space-x-2">
        <!-- Label -->
        <div class="flex flex-grow-1 w-1/2 flex-col space-y-2">
            <p class="text-sm font-medium text-gray-900">
                {{ $resource->label }}
            </p>
            @if($resource->tagline)
                <p class="text-xs font-medium text-gray-500 py-0.5 px-1 rounded-md bg-gray-100 flex space-x-2">
                    <x-svg.info-circle class="h-4 w-4"/>
                    <span>{{ $resource->tagline }}</span>
                </p>
            @endif
        </div>

        @if($resource->type != 'file')
            <div class="flex-grow-1 w-1/2">
            @if($resource->value && !$is_editing)
                <!-- Value -->
                    <div class="flex space-x-2 items-center">
                        @if($resource->type != 'url')
                        <p class="flex-grow-1 w-full text-sm font-medium text-secondary-500 bg-secondary-100 rounded-md p-2">
                            @if($resource->type == 'time')
                                {{ Carbon\Carbon::parse($resource->value)->format('g:i A') }}
                            @elseif($resource->type == 'date')
                                {{ Carbon\Carbon::parse($resource->value)->isoFormat('MMMM DD, YYYY') }}
                            @else
                            {{ $resource->value }}
                            @endif
                        </p>
                        <button
                            type="button"
                            wire:click="$set('is_editing', true)"
                            class="rounded-full p-2 h-8 w-8 bg-gray-100 text-gray-500 hover:bg-secondary-500 hover:text-white transition-all duration-100 ease-in-out"
                            tooltip="Edit Resource Value"
                            tooltip-p="left"
                        >
                            <x-svg.edit class="w-4 h-4"/>
                        </button>
                        @else
                            <a tooltip="Open URL in new tab" href="{{ $resource->value }}" target="_blank" class="flex shadow-sm rounded-md w-full">
                                <div class="flex-shrink-0 flex items-center justify-center w-16 bg-gray-100 text-gray-500 text-sm font-medium rounded-l-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                    <div class="flex-1 px-6 py-2 text-sm truncate">
                                        <span class="text-gray-900 font-medium hover:text-gray-600">{{ $resource->value }}</span>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                @else
                    <div class="flex items-center space-x-2 space-between">
                        <div class="flex-grow-1 w-full">
                            @switch($resource->type)
                                @case('text')
                                <x-input.text
                                    wire:model.defer="value"
                                    placeholder="Enter a value..."
                                />
                                @break
                                @case('number')
                                <x-input.text
                                    wire:model.defer="value"
                                    type="number"
                                    placeholder="Enter a value..."
                                />
                                @break
                                @case('date')
                                <x-input.date
                                    wire:model.defer="value"
                                    placeholder="Pick a date..."
                                />
                                @break
                                @case('time')
                                <x-input.text
                                    type="time"
                                    wire:model.defer="value"
                                    placeholder="Enter a time..."
                                />
                                @break
                            @endswitch
                        </div>
                        <button type="button" wire:click="updateValue"
                            class="mt-1 p-2 h-8 w-8 rounded-full text-secondary-500 bg-secondary-100 hover:bg-secondary-500 hover:text-white transition-all duration-100 ease-in-out"
                            tooltip="Save Resource Value"
                            tooltip-p="left"
                        >
                            <x-svg.check class="h-4 w-4"/>
                        </button>
                    </div>
                @endif
            </div>
        @else
        <!-- File -->
            <div class="flex flex-grow-1 w-1/2 space-x-2 justify-between">
                <div class="flex flex-grow-1 w-full items-center space-x-2">
                    @if($resource->files->first()->getSimpleFileType() == 'image' || $resource->files->first()->getSimpleFileType() == 'svg')
                        @switch($resource->files->first()->getSimpleFileType())
                            @case('image')
                            <a href="{{ $resource->files->first()->url() }}" target="_blank"
                                class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                            >
                                <img src="{{ $resource->files->first()->url() }}" alt="" class="object-scale-down h-12 w-12 rounded-md" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                                <x-svg.files.image class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                            </a>
                            @break

                            @case('svg')
                            <a href="{{ $resource->files->first()->url() }}" target="_blank"
                                class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                            >
                                <img src="{{ $resource->files->first()->url() }}" alt="" class="group-hover:opacity-75 h-12 w-12 object-scale-down" x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
                                <x-svg.files.code class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                            </a>
                            @break
                        @endswitch
                    @else
                        <x-dynamic-component :component="'svg.files.'.$resource->files->first()->getSimpleFileType()" class="h-5 w-5 text-gray-300"/>
                    @endif
                    <div>
                        <p class="font-medium text-sm text-left flex-col">
                            {{ $resource->files->first()->src }}
                        </p>
                    </div>
                </div>
                <div class="flex space-x-6 items-center">
                    <div>
                        <a class="block w-full text-left px-1 py-1 rounded-md text-sm text-gray-500 hover:bg-secondary-100 hover:text-secondary-500" href="{{ $resource->files->first()->url() }}" download="{{ $resource->files->first()->src }}">
                            <x-svg.download class="h-5 w-5"/>
                        </a>
                    </div>
                    <div>
                        <a href="{{ $resource->files->first()->url() }}" target="_blank" class="block w-full text-left px-1 py-1 rounded-md text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-600">
                            <x-svg.external-link class="h-5 w-5"/>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <x-menu.three-dot>
        <div>
            @if($showMenuContent)
                @livewire('admin.resources.edit', ['resource' => $resource, 'model' => $model, 'button_type' => 'inline-menu'], key('resource-edit-' . $resource->id))
                <x-modal
                    wire:click="destroy"
                    triggerText="Delete Resource"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete Resource"
                >
                    Are you sure you want to delete this resource?<br><br>
                    <strong>{{ $resource->label }}</strong>
                    <div class="rounded-md p-3 bg-gray-100 mt-4 text-xs">
                        File will not be deleted.
                    </div>
                </x-modal>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </div>
    </x-menu.three-dot>
</div>
