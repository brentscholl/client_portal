<x-slideout.wrapper>
    <x-button
        type="button"
        btn="secondary"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Resource"
        tooltip-p="left"
    >
        <x-svg.plus-circle class="h-5 w-5 text-white"/>
    </x-button>
    <form wire:submit.prevent="createResource" autocomplete="off">
        <x-slideout
            title="Create a new Resource"
            subtitle="{{ optional($model)->title }}"
            saveBtn="Create Resource"
        >
            @include('errors.list')
            <div x-data="{ 'type': @entangle('type') }" class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    <div>
                        <x-input.radio-set label="Resource Type">
                            <x-input.radio
                                wire:model="type"
                                value="text"
                                label="Text Input"
                                description="Provide Client with a text input"
                                :first="true"
                            />
                            <x-input.radio
                                wire:model="type"
                                value="number"
                                label="Number Input"
                                description="Provide Client with a number input"
                            />
                            <x-input.radio
                                x-model="type"
                                wire:model="type"
                                value="date"
                                label="Date Input"
                                description="Provide Client with a date picker"
                            />
                            <x-input.radio
                                wire:model="type"
                                value="time"
                                label="Time Input"
                                description="Provide Client with a time picker"
                            />
                            <x-input.radio
                                wire:model="type"
                                value="url"
                                label="URL"
                                description="Add a URL that can be visited by Client"
                            />
                            <x-input.radio
                                wire:model="type"
                                value="file"
                                label="File Download"
                                description="Add a file that can be downloaded by Client"
                                :last="true"
                            />
                        </x-input.radio-set>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="label"
                            label="Label"
                            :required="true"
                            placeholder=""
                            class=""
                        />
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="tagline"
                            label="Tagline"
                            placeholder="Provide more info if needed"
                            class=""
                        />
                    </div>

                    @if($type != 'file')
                        <div>
                            <div x-cloak x-show="type == 'text'">
                                <x-input.text
                                    wire:model.defer="value"
                                    label="Default Value (optional)"
                                    placeholder="Enter a value..."
                                />
                            </div>
                            <div x-cloak x-show="type == 'number'">
                                <x-input.text
                                    wire:model.defer="value"
                                    label="Default Value (optional)"
                                    type="number"
                                    placeholder="Enter a value..."
                                />
                            </div>
                            <div x-cloak x-show="type == 'date'">
                                <x-input.date
                                    wire:model.lazy="value"
                                    label="Default Value (optional)"
                                    placeholder="Pick a date..."
                                />
                            </div>
                            <div x-cloak x-show="type == 'time'">
                                <x-input.text
                                    type="time"
                                    label="Default Value (optional)"
                                    wire:model.defer="value"
                                    placeholder="Enter a time..."
                                />
                            </div>
                            <div x-cloak x-show="type == 'url'">
                                <x-input.text
                                    wire:model.defer="value"
                                    wire:key="url"
                                    label="URL"
                                    :required="false"
                                    placeholder="https://xd.adobe.com/view/example/"
                                />
                            </div>
                        </div>
                    @endif


                    @if($type == 'file')
                        <div>
                            <label>File Download</label>
                            <div class="flex flex-col space-y-3 border rounded-md p-3 mt-1">
                                @if(!$uploaded_file)
                                    <div>
                                    @if($selected_file)
                                        <!-- Selected File -->
                                            <div class="flex space-x-2 justify-between">
                                                <div class="flex items-center space-x-2">
                                                    @if($selected_file->getSimpleFileType() == 'image' || $selected_file->getSimpleFileType() == 'svg')
                                                        @switch($selected_file->getSimpleFileType())
                                                            @case('image')
                                                            <a href="{{ $selected_file->url() }}" target="_blank"
                                                                class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                                                            >
                                                                <img src="{{ $selected_file->url() }}" alt=""
                                                                    class="object-scale-down h-12 w-12 rounded-md"
                                                                    x-state:on="Current" x-state:off="Default"
                                                                    x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                                                                <x-svg.files.image
                                                                    class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                                                            </a>
                                                            @break

                                                            @case('svg')
                                                            <a href="{{ $selected_file->url() }}" target="_blank"
                                                                class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                                                            >
                                                                <img src="{{ $selected_file->url() }}" alt=""
                                                                    class="group-hover:opacity-75 h-12 w-12 object-scale-down"
                                                                    x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
                                                                <x-svg.files.code
                                                                    class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                                                            </a>
                                                            @break
                                                        @endswitch
                                                    @else
                                                        <x-dynamic-component
                                                            :component="'svg.files.'.$selected_file->getSimpleFileType()"
                                                            class="h-5 w-5 text-gray-300"/>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-sm text-left flex-col">
                                                            {{ $selected_file->src }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-6 items-center">

                                                    <div class="flex-col items-center">
                                                        <p class="text-xs text-gray-400"><small>Type</small></p>
                                                        <p class="text-uppercase text-xs">
                                                            {{ $selected_file->extension }}
                                                        </p>
                                                    </div>

                                                    <div class="flex-col items-center">
                                                        <p class="text-xs text-gray-400"><small>Size</small></p>
                                                        <p class="text-uppercase text-xs">
                                                            {{ $selected_file->formatSize() }}
                                                        </p>
                                                    </div>
                                                    @if($selected_file->getSimpleFileType() == 'image')
                                                        <div class="flex-col items-center">
                                                            <p class="text-xs text-gray-400"><small>Dimensions</small>
                                                            </p>
                                                            <p class="text-uppercase text-xs">
                                                                {{ $selected_file->dimensions() }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                                <button type="button"
                                                    wire:click="unassignFile()"
                                                    class="border-transparent bg-transparent text-red-500 text-xs rounded-full hover:text-red-700"
                                                >
                                                    Remove
                                                </button>
                                            </div><!-- top container ends -->
                                        @else
                                            <x-modal
                                                type="add"
                                                cancelText="Cancel"
                                                triggerClass="flex ml-auto mr-auto text-xs text-gray-400 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
                                                modalTitle="Select File"
                                                :showSubmitBtn="false"
                                                size="md"
                                                wire:click="loadModal"
                                            >
                                                <x-slot name="triggerText">
                                                    <x-svg.plus-circle class="h-4 w-4 mr-2"/>
                                                    Select Uploaded File
                                                </x-slot>
                                                <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
                                                    @if($modalOpen)
                                                        <ul role="list" class="divide-y divide-gray-200 mr-10"
                                                            id="{{now()}}">
                                                            @forelse($assignable_files as $file)
                                                                <li class="py-4 flex items-center justify-between w-full">
                                                                    <div class="flex space-x-2 justify-between">
                                                                        <div class="flex items-center space-x-2">
                                                                            @if($file->getSimpleFileType() == 'image' || $file->getSimpleFileType() == 'svg')
                                                                                @switch($file->getSimpleFileType())
                                                                                    @case('image')
                                                                                    <a href="{{ $file->url() }}"
                                                                                        target="_blank"
                                                                                        class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                                                                                    >
                                                                                        <img src="{{ $file->url() }}"
                                                                                            alt=""
                                                                                            class="object-scale-down h-12 w-12 rounded-md"
                                                                                            x-state:on="Current"
                                                                                            x-state:off="Default"
                                                                                            x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                                                                                        <x-svg.files.image
                                                                                            class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                                                                                    </a>
                                                                                    @break

                                                                                    @case('svg')
                                                                                    <a href="{{ $file->url() }}"
                                                                                        target="_blank"
                                                                                        class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                                                                                    >
                                                                                        <img src="{{ $file->url() }}"
                                                                                            alt=""
                                                                                            class="group-hover:opacity-75 h-12 w-12 object-scale-down"
                                                                                            x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
                                                                                        <x-svg.files.code
                                                                                            class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                                                                                    </a>
                                                                                    @break
                                                                                @endswitch
                                                                            @else
                                                                                <x-dynamic-component
                                                                                    :component="'svg.files.'.$file->getSimpleFileType()"
                                                                                    class="h-5 w-5 text-gray-300"/>
                                                                            @endif
                                                                            <div>
                                                                                <p class="font-medium text-sm text-left flex-col">
                                                                                    {{ $file->src }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="flex space-x-6 items-center">

                                                                            <div class="flex-col items-center">
                                                                                <p class="text-xs text-gray-400"><small>Type</small>
                                                                                </p>
                                                                                <p class="text-uppercase text-xs">
                                                                                    {{ $file->extension }}
                                                                                </p>
                                                                            </div>

                                                                            <div class="flex-col items-center">
                                                                                <p class="text-xs text-gray-400"><small>Size</small>
                                                                                </p>
                                                                                <p class="text-uppercase text-xs">
                                                                                    {{ $file->formatSize() }}
                                                                                </p>
                                                                            </div>
                                                                            @if($file->getSimpleFileType() == 'image')
                                                                                <div class="flex-col items-center">
                                                                                    <p class="text-xs text-gray-400">
                                                                                        <small>Dimensions</small></p>
                                                                                    <p class="text-uppercase text-xs">
                                                                                        {{ $file->dimensions() }}
                                                                                    </p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div><!-- top container ends -->
                                                                    <div x-data="{ fileLoading: false }">
                                                                        <button
                                                                            wire:click="assignFile({{ $file->id }})"
                                                                            type="button"
                                                                            @click="fileLoading = true"
                                                                            x-bind:disabled="fileLoading"
                                                                            class="rounded-full border border-secondary-500 bg-white text-secondary-500 p-2 flex items-center justify-center h-8 w-8 hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                                                        >
                                                                    <span x-cloak x-show="fileLoading == false">
                                                                        <x-svg.plus class="w-5 h-5"/>
                                                                    </span>
                                                                            <span x-show="fileLoading == true">
                                                                        <x-svg.spinner class="w-5 h-5"/>
                                                                    </span>
                                                                        </button>
                                                                    </div>
                                                                </li>
                                                            @empty
                                                                No resource files available. Please upload a file
                                                            @endforelse
                                                        </ul>
                                                    @else
                                                        <div class="pr-14 my-6 w-full">
                                                            <x-svg.spinner class="h-8 w-8 text-secondary-500 mx-auto"/>
                                                        </div>
                                                    @endif
                                                </div>
                                            </x-modal>
                                        @endif
                                    </div>
                                @endif
                                @if(!$uploaded_file && !$selected_file)
                                    <p class="text-center">or</p>
                                @endif
                                @if(!$selected_file)
                                    <div>
                                        <x-input.file-upload
                                            wire:key="{{ now() }}"
                                            wire:model="uploaded_file"
                                            :required="false"
                                            placeholder=""
                                            uploader_type="file"
                                            class=""/>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <x-skeleton.input.radio-set>
                        <x-skeleton.input.radio :first="true"/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio/>
                        <x-skeleton.input.radio :last="true"/>
                    </x-skeleton.input.radio-set>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
