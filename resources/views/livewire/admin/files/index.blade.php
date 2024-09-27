<div class="flex-1 flex overflow-hidden">
    <main class="flex-1 overflow-y-auto mr-96" style="height: calc(100vh - 4rem)">
        <div class="pt-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex">
                <h1 class="flex-1 text-2xl font-bold text-gray-900">Files</h1>
            </div>

            <!-- Tabs -->
            <div class="mt-3 sm:mt-2">
                <div class="hidden sm:block">
                    <div class="flex items-center border-b border-gray-200">
                        <nav class="flex-1 -mb-px flex space-x-6 xl:space-x-8" aria-label="Tabs">

                            <button wire:click="changeTab('all')" type="button" aria-current="page" class="{{ $tab == 'all' ? 'border-secondary-500 text-secondary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;border-secondary-500 text-secondary-600&quot;, Default: &quot;border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200&quot;">
                                All
                            </button>

                            <button wire:click="changeTab('images')" type="button" aria-current="page" class="{{ $tab == 'images' ? 'border-secondary-500 text-secondary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;border-secondary-500 text-secondary-600&quot;, Default: &quot;border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200&quot;">
                                Images
                            </button>

                            <button wire:click="changeTab('documents')" type="button" class="{{ $tab == 'documents' ? 'border-secondary-500 text-secondary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" x-state-description="undefined: &quot;border-secondary-500 text-secondary-600&quot;, undefined: &quot;border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200&quot;">
                                Documents
                            </button>

                            <button wire:click="changeTab('video')" type="button" class="{{ $tab == 'video' ? 'border-secondary-500 text-secondary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" x-state-description="undefined: &quot;border-secondary-500 text-secondary-600&quot;, undefined: &quot;border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200&quot;">
                                Videos
                            </button>

                            <button wire:click="changeTab('audio')" type="button" class="{{ $tab == 'audio' ? 'border-secondary-500 text-secondary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" x-state-description="undefined: &quot;border-secondary-500 text-secondary-600&quot;, undefined: &quot;border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200&quot;">
                                Audio
                            </button>

                            <button wire:click="changeTab('resource')" type="button" class="{{ $tab == 'resource' ? 'border-secondary-500 text-secondary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" x-state-description="undefined: &quot;border-secondary-500 text-secondary-600&quot;, undefined: &quot;border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-200&quot;">
                                <x-svg.resource class="h-4 w-4 inline-block mr-2"/>
                                Resource Files
                            </button>

                        </nav>
                        <div class="hidden ml-6 bg-gray-100 p-0.5 rounded-lg items-center sm:flex">
                            <button tooltip="List View" wire:click="$set('view_mode', 'list')" type="button" class="{{ $view_mode == 'list' ? 'bg-white shadow-sm' : 'hover:bg-white hover:shadow-sm' }} p-1.5 rounded-md text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500">
                                <svg class="h-5 w-5" x-description="Heroicon name: solid/view-list" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Use list view</span>
                            </button>
                            <button tooltip="Thumbnail View" wire:click="$set('view_mode', 'grid')" type="button" class="{{ $view_mode == 'grid' ? 'bg-white shadow-sm' : 'hover:bg-white hover:shadow-sm' }} ml-0.5 p-1.5 rounded-md text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-secondary-500">
                                <svg class="h-5 w-5" x-description="Heroicon name: solid/view-grid" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                                <span class="sr-only">Use grid view</span>
                            </button>
                        </div>
{{--                        <div class="ml-2">--}}
{{--                            @livewire('admin.files.upload', ['location' => 'file-index'])--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            <section class="mt-8 pb-16" aria-labelledby="gallery-heading">
                @if($tab == 'resource')
                    <div class="flex justify-end w-full h-auto mb-4">
                        @livewire('admin.files.upload', ['model' => null, 'is_resource' => true], key('upload-file'))
                    </div>
                @endif
            @if($files->count() > 0)
                @if($view_mode == 'grid')
                    <!-- Grid -->
                        <ul role="list" class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 md:grid-cols-4 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                            @foreach($files as $file)
                                <li class="relative">
                                    <div class="{{ $file->id == optional($selected_file)->id ? 'ring-2 ring-offset-2 ring-secondary-500' : 'focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 focus-within:ring-secondary-500' }} group block w-full aspect-w-10 aspect-h-7 rounded-lg bg-gray-100 overflow-hidden" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;ring-2 ring-offset-2 ring-secondary-500&quot;, Default: &quot;focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-offset-gray-100 focus-within:ring-secondary-500&quot;">
                                        @switch($file->getSimpleFileType())
                                            @case('image')
                                            <div class="flex flex-col items-center justify-center border-2 rounded-md text-gray-400">
                                                <img src="{{ $file->url() }}" alt="" class="object-cover pointer-events-none h-full w-full rounded-md" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                                                <x-svg.files.image class="h-6 w-6 absolute bottom-4 right-4 opacity-50"/>
                                            </div>
                                            @break

                                            @case('svg')
                                            <div class="flex flex-col items-center justify-center border-2 rounded-md text-gray-400">
                                                <img src="{{ $file->url() }}" alt="" class="group-hover:opacity-75 object-scale-down pointer-events-none" x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
                                                <x-svg.files.code class="h-6 w-6 absolute bottom-4 right-4 opacity-50"/>
                                            </div>
                                            @break

                                            @case('pdf')
                                            @case('word')
                                            @case('csv')
                                            @case('excel')
                                            @case('code')
                                            @case('zip')
                                            @case('audio')
                                            @case('video')
                                            @case('file-alt')
                                            <div class="flex flex-col items-center justify-center group-hover:opacity-75 object-cover pointer-events-none bg-gray-300 text-gray-400 rounded-md p-4">
                                                <x-dynamic-component :component="'svg.files.'.$file->getSimpleFileType()" class="h-12 w-12"/>
                                            </div>
                                            @break

                                            @default
                                            <div class="flex flex-col items-center justify-center group-hover:opacity-75 object-cover pointer-events-none bg-gray-300 text-gray-400 rounded-md p-4">
                                                <x-svg.files.file-alt class="w-12 h-12"/>
                                            </div>
                                            @break
                                        @endswitch

                                        <button wire:click="selectFile({{ $file }})" type="button" class="absolute inset-0 focus:outline-none">
                                            <span class="sr-only">View details for {{ $file->src }}</span>
                                        </button>
                                    </div>
                                    @if($file->is_resource)
                                        <span class="absolute left-3 top-40 rounded-full h-5 w-5 flex justify-center items-center bg-amber-100"
                                            tooltip="This is a Resource File" tooltip-p="right"><x-svg.resource class="h-4 w-4 text-amber-900"/></span>
                                    @endif
                                    <p tooltip="Original file name" tooltip-p="left" class="mt-2 block text-sm font-medium text-gray-900 truncate">{{ $file->src }}</p>
                                    <p tooltip="File size" tooltip-p="left" class="block text-sm font-medium text-gray-500">{{ $file->formatSize() }}</p>
                                </li>
                            @endforeach
                        </ul>
                @else
                    <!-- List -->
                        <ul role="list" class="grid grid-cols-1 gap-y-2">
                            @foreach($files as $file)
                                <li class="relative rounded-lg border border-gray-200 bg-white pr-3 pl-3 py-3 shadow-sm">
                                    <div class="flex space-x-4 items-center">
                                        @switch($file->getSimpleFileType())
                                            @case('image')
                                            <button
                                                wire:click="selectFile({{ $file }})"
                                                type="button"
                                                class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                                            >
                                                <img src="{{ $file->url() }}" alt="" class="object-scale-down h-12 w-12 rounded-md" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                                                <x-svg.files.image class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                                            </button>
                                            @break

                                            @case('svg')
                                            <button
                                                wire:click="selectFile({{ $file }})"
                                                type="button"
                                                class="flex flex-col relative items-center justify-center border-2 rounded-md text-gray-400"
                                            >
                                                <img src="{{ $file->url() }}" alt="" class="group-hover:opacity-75 h-12 w-12 object-scale-down" x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
                                                <x-svg.files.code class="h-4 w-4 absolute bottom-0.5 right-0.5 opacity-75"/>
                                            </button>
                                            @break

                                            @case('pdf')
                                            @case('word')
                                            @case('csv')
                                            @case('excel')
                                            @case('code')
                                            @case('zip')
                                            @case('audio')
                                            @case('video')
                                            @case('file-alt')
                                            <button
                                                wire:click="selectFile({{ $file }})"
                                                type="button"
                                                class="flex flex-col items-center justify-center group-hover:opacity-75 object-cover bg-gray-300 text-gray-400 rounded-md p-4"
                                            >
                                                <x-dynamic-component :component="'svg.files.'.$file->getSimpleFileType()" class="h-5 w-5"/>
                                            </button>
                                            @break

                                            @default
                                            <button
                                                wire:click="selectFile({{ $file }})"
                                                type="button"
                                                class="flex flex-col items-center justify-center group-hover:opacity-75 object-cover bg-gray-300 text-gray-400 rounded-md p-4"
                                            >
                                                <x-svg.files.file-alt class="w-5 h-5"/>
                                            </button>
                                            @break
                                        @endswitch
                                        <div class="flex flex-1 justify-between space-x-4 relative">
                                            <div class="flex flex-col justify-center space-y-2">
                                                <p class="text-sm text-left flex-col">
                                                    <button
                                                        tooltip="View File Details"
                                                        wire:click="selectFile({{ $file }})"
                                                        type="button"
                                                        class="font-medium hover:text-secondary-500 transition-all duration-100 ease-in-out"
                                                    >
                                                        {{ $file->src }}
                                                    </button>
                                                </p>
                                                @if( $file->answers->count() > 0 ||  $file->tasks->count() > 0 || $file->is_resource)
                                                    <div class="flex space-x-2 items-center">
                                                        <span class="text-xs">Attached to:</span>
                                                        @if($file->tasks->count() > 0)
                                                            <a href="{{ route('admin.tasks.show', $file->tasks()->first()->id) }}" class="group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                                            <span tooltip="View File's Task" class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                                                Task
                                                            </span>
                                                            </a>
                                                        @endif
                                                        @if($file->answers->count() > 0)
                                                            <a href="{{ route('admin.questions.show', $file->answers()->first()->question->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                                            <span tooltip="View File's Answer" class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                                                Answer
                                                            </span>
                                                            </a>
                                                        @endif
                                                        @if($file->is_resource)
                                                            <span tooltip="This file is a Resource File" class=" group rounded-full bg-amber-100 text-xs text-amber-500 text-xs font-medium py-0.5 px-2 flex space-x-2">
                                                                    <x-svg.resource class="h-4 w-4 inline-block mr-2"/>Resource
                                                                </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex flex-col space-y-2 items-end">
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
                                                </div>
                                                <div class="text-xs text-gray-500 flex space-x-2">
                                                    <span class="text-gray-400">Uploaded {{ $file->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <span class="relative block w-full border-2 border-gray-200 border-dashed rounded-lg p-12 text-center">
                        <x-svg.files class="mx-auto h-12 w-12 text-gray-200"/>
                        <span class="mt-2 block text-sm font-medium text-gray-300">
                          No files uploaded
                        </span>
                    </span>
                @endif
            </section>
        </div>
    </main>

    <!--*********************************-->
    <!----- Details sidebar ----->
    <!--*********************************-->
    @if($selected_file)
        @include('admin.files.details-sidebar')
    @endif
</div>
