<div class="relative rounded-lg border border-gray-200 bg-white pr-6 pl-8 py-5 shadow-sm">
    <!-- Card Icon -->
    @if($show_card_icon)
        <div class="rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
            <x-svg.files class="h-4 w-4 text-gray-500"/>
        </div>
    @endif

    <div class="flex flex-col space-y-3">

        <!---- File Top Container ---->
        <div class="flex space-x-2 justify-between">
            <div class="flex items-center space-x-2">
                @if($file->getSimpleFileType() == 'image' || $file->getSimpleFileType() == 'svg')
                    @switch($file->getSimpleFileType())
                        @case('image')
                        <a tooltip="Open file in new tab" href="{{ $file->url() }}" target="_blank"
                            class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                        >
                            <img src="{{ $file->url() }}" alt="" class="object-scale-down h-12 w-12 rounded-md" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                            <x-svg.files.image class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                        </a>
                        @break

                        @case('svg')
                        <a tooltip="Open file in new tab" href="{{ $file->url() }}" target="_blank"
                            class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                        >
                            <img src="{{ $file->url() }}" alt="" class="group-hover:opacity-75 h-12 w-12 object-scale-down" x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
                            <x-svg.files.code class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                        </a>
                        @break
                    @endswitch
                @else
                    <x-dynamic-component :component="'svg.files.'.$file->getSimpleFileType()" class="h-5 w-5 text-gray-300"/>
                @endif
                <div>
                    <a tooltip="View File details" href="{{ route('admin.files.show', $file->id) }}" class="text-gray-500 font-medium text-sm text-left flex-col hover:text-secondary-500 s-transition">
                        {{ $file->src }}
                    </a>
                </div>
            </div>
            <div class="flex space-x-6 items-center">

                <div class="flex-col items-center">
                    <p class="text-xs text-gray-400"><small>Type</small></p>
                    <p class="text-uppercase text-xs">
                        {{ $file->extension }}
                    </p>
                </div>

                <div class="flex-col items-center">
                    <p class="text-xs text-gray-400"><small>Size</small></p>
                    <p class="text-uppercase text-xs">
                        {{ $file->formatSize() }}
                    </p>
                </div>
                @if($file->getSimpleFileType() == 'image')
                    <div class="flex-col items-center">
                        <p class="text-xs text-gray-400"><small>Dimensions</small></p>
                        <p class="text-uppercase text-xs">
                            {{ $file->dimensions() }}
                        </p>
                    </div>
                @endif
                <div>
                    <a tooltip="Download File" class="block w-full text-left px-1 py-1 rounded-md text-sm text-gray-500 hover:bg-secondary-100 hover:text-secondary-500" href="{{ $file->url() }}" download="{{ $file->src }}">
                        <x-svg.download class="h-5 w-5"/>
                    </a>
                </div>
                <div>
                    <a tooltip="Open file in new tab" href="{{ $file->url() }}" target="_blank" class="block w-full text-left px-1 py-1 rounded-md text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-600">
                        <x-svg.external-link class="h-5 w-5"/>
                    </a>
                </div>
            </div>
        </div><!-- top container ends -->
        @if($file->caption)
            <div class="flex space-x-2 bg-gray-100 p-2 rounded-md mt-3 text-gray-400">
                <x-svg.info-circle class="w-4 h-4"/>
                <p class="text-gray-400 text-xs">
                    {{ $file->caption }}
                </p>
            </div>
        @endif
        <div class="flex space-x-2 justify-between items-center">
            @if( $file->tasks->count() > 0 ||  $file->answers->count() > 0)
                <div class="flex space-x-2 items-center">
                    <span class="text-xs">Attached to:</span>
                        @if($file->tasks->count() > 0)
                            <a tooltip="View File's Task" href="{{ route('admin.tasks.show', $file->tasks()->first()->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                    Task
                                </span>
                            </a>
                        @endif
                        @if($file->answers->count() > 0)
                        <a tooltip="View File's Answer" href="{{ route('admin.questions.show', $file->answers()->first()->question->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                            <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                Answer
                            </span>
                        </a>
                        @endif
                </div>
            @endif
            <div class="text-xs text-gray-500 flex space-x-2">
                <span>Uploaded by:</span>
                <a tooltip="View User" href="{{ route('admin.users.show', $file->user->id) }}" class="text-gray-600 hover:text-secondary-500 transition-all duration-100 ease-in-out">{{ $file->user->fullname }}</a>
                <span class="text-gray-400">{{ $file->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>

    <!--- Menu ---->
    <x-menu.three-dot>
        <div>
            @if($showMenuContent)
                @livewire('admin.files.edit', ['file' => $file, 'button_type' => 'inline-menu'], key('file-edit-' . $file->id))
                <x-modal
                    wire:click="removeFile({{ $file->id }})"
                    triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                    modalTitle="Delete File"
                    triggerText="Delete File"
                >
                    Are you sure you want to delete this file?<br><br>
                    <strong>{{ $file->src }}</strong>
                </x-modal>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </div>
    </x-menu.three-dot>
</div>
