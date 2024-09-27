<aside class="fixed top-16 right-0 hidden w-96 bg-white shadow-inner p-8 pt-8 border-l border-gray-200 overflow-y-auto lg:block" style="height: calc(100vh - 4rem)">
    <div class="pb-16 space-y-6">
        <div>
            <div class="block w-full aspect-w-10 aspect-h-7 rounded-lg overflow-hidden">
                @switch($selected_file->getSimpleFileType())
                    @case('image')
                    <div class="flex flex-col items-center justify-center border-2 rounded-md text-gray-400">
                        <img src="{{ $selected_file->url() }}" alt="" class="object-cover pointer-events-none h-full w-full rounded-md" x-state:on="Current" x-state:off="Default" x-state-description="Current: &quot;&quot;, Default: &quot;group-hover:opacity-75&quot;">
                        <x-svg.files.image class="h-6 w-6 absolute bottom-4 right-4 opacity-50"/>
                    </div>
                    @break

                    @case('svg')
                    <div class="flex flex-col items-center justify-center border-2 rounded-md text-gray-400">
                        <img src="{{ $selected_file->url() }}" alt="" class="group-hover:opacity-75 object-scale-down pointer-events-none" x-state-description="undefined: &quot;&quot;, undefined: &quot;group-hover:opacity-75&quot;">
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
                        <x-dynamic-component :component="'svg.files.'.$selected_file->getSimpleFileType()" class="h-12 w-12"/>
                    </div>
                    @break

                    @default
                    <div class="flex flex-col items-center justify-center group-hover:opacity-75 object-cover pointer-events-none bg-gray-300 text-gray-400 rounded-md p-4">
                        <x-svg.files.file-alt class="w-12 h-12"/>
                    </div>
                    @break
                @endswitch
            </div>
            <div class="mt-4 flex items-start justify-between">
                <div>
                    <h2 tooltip="Original filename" tooltip-p="left" class="text-lg font-medium text-gray-900"><span class="sr-only">Details for </span>
                        {{ $selected_file->src }}
                    </h2>
                    <p tooltip-p="left" tooltip="File size" class="text-sm font-medium text-gray-500">{{ $selected_file->formatSize() }}</p>
                </div>
                @livewire('admin.files.edit', ['file' => $selected_file], key('file-edit'))
            </div>
        </div>
        @if($selected_file->is_resource)
            <div class="rounded-full py-1 px-3 bg-amber-100 text-amber-500 text-sm font-medium text-center">
                <x-svg.resource class="h-4 w-4 inline-block mr-2"/>
                Resource File
            </div>
        @endif
        <div>
            <h3 class="font-medium text-gray-900">Information</h3>
            <dl class="mt-2 border-t border-b border-gray-200 divide-y divide-gray-200">

                <div class="py-3 flex items-center justify-between text-sm font-medium">
                    <dt class="text-gray-500">Uploaded by</dt>
                    <dd class="text-gray-900">
                        <a tooltip="View User" href="{{ route('admin.users.show', $selected_file->user_id) }}" class="hover:text-secondary-500">{{ $selected_file->user->full_name }}</a>
                    </dd>
                </div>

                @if($selected_file->tasks->count() > 0 || $selected_file->answers->count() > 0)
                    <?php
                    if ( $selected_file->tasks->count() > 0 ) {
                        $client = $selected_file->tasks()->first()->client;
                    } elseif ( $selected_file->answers->count() > 0 ) {
                        $client = $selected_file->answers()->first()->client;
                    }
                    ?>
                    @if($client)
                    <div class="py-3 flex items-center justify-between text-sm font-medium">
                        <dt class="text-gray-500">Client</dt>
                        <dd class="text-gray-900">
                            <div class="flex items-center">
                                <img src="{{ $client->avatarUrl() }}" alt="" class="w-8 h-8 p-1 bg-gray-800 rounded-full">
                                <a tooltip="View Client" href="{{ route('admin.clients.show', $client->id) }}" class="ml-4 text-sm font-medium text-gray-900 hover:text-secondary-500">{{ $client->title }}</a>
                            </div>
                        </dd>
                    </div>
                    @endif
                @endif

                <div class="py-3 flex justify-between text-sm font-medium">
                    <dt class="text-gray-500">Type</dt>
                    <dd class="text-gray-900">{{ $selected_file->extension }}</dd>
                </div>
                <div class="py-3 flex justify-between text-sm font-medium">
                    <dt class="text-gray-500">Created</dt>
                    <dd class="text-gray-900">{{ $selected_file->created_at->format('M d, Y @ g:i a') }}</dd>
                </div>

                <div class="py-3 flex justify-between text-sm font-medium">
                    <dt class="text-gray-500">Last modified</dt>
                    <dd class="text-gray-900">{{ $selected_file->updated_at->format('M d, Y @ g:i a') }}</dd>
                </div>

                @if($selected_file->getSimpleFileType() == 'image')
                    <div class="py-3 flex justify-between text-sm font-medium">
                        <dt class="text-gray-500">Dimensions</dt>
                        <dd class="text-gray-900">{{ $selected_file->dimensions() }}</dd>
                    </div>
                @endif

            </dl>
        </div>
        @if( $selected_file->tasks->count() > 0 || $selected_file->answers->count() > 0 || $selected_file->resources->count() > 0)
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Attached To:</h3>
                @if($selected_file->tasks->count() > 0)
                    <a tooltip="View File's Task" href="{{ route('admin.tasks.show', $selected_file->tasks()->first()->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                            <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                Task: {{ Str::limit($selected_file->tasks()->first()->title, 30) }}
                            </span>
                    </a>
                @endif
                @if($selected_file->answers->count() > 0)
                    <a tooltip="View File's Answer" href="{{ route('admin.questions.show', $selected_file->answers()->first()->question->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                            <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                Answer: {{ Str::limit($selected_file->answers()->first()->question->body, 30) }}
                            </span>
                    </a>
                @endif
                @if($selected_file->resources->count() > 0)
                    @foreach($selected_file->resources as $resource)
                        <a tooltip="View File's Resource" href="{{ route('admin.'. strtolower(str_replace('App\Models\\', '', $resource->resourceable_type)) .'s.show', ['id' => $resource->resourceable_id, 'tab' => 'resources']) }}" class=" mt-2 group rounded-full bg-secondary-100 text-xs text-secondary-500 py-0.5 px-2 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                                <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">
                                    {{ str_replace('App\Models\\', '', $resource->resourceable_type) }}: {{ Str::limit($resource->resourceable->title, 30) }}
                                </span>
                        </a>
                    @endforeach
                @endif
            </div>
        @else
            <div>
                <h3 class="font-medium text-gray-300 mb-2">File has no attachments</h3>
            </div>
        @endif
        <div>
            <h3 class="font-medium text-gray-900">Description</h3>
            <div class="mt-2 flex items-center justify-between">
                @if($is_adding_description)
                    <x-input.textarea
                        wire:model.defer="caption"
                    />
                    <button tooltip="Save Description" tooltip-p="left" wire:click="saveDescription" type="button" class="bg-green-500 text-white rounded-full h-8 w-8 flex items-center justify-center hover:bg-green-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-secondary-500">
                        <x-svg.check class="h-5 w-5"/>
                    </button>
                @else
                    @if($selected_file->caption)
                        <p class="text-sm text-gray-500 italic">{{ $selected_file->caption }}</p>
                    @else
                        <p class="text-sm text-gray-500 italic">Add a description to this file.</p>
                    @endif
                    <button tooltip="Edit File's Description" tooltip-p="left" wire:click="$set('is_adding_description', true)" type="button" class="bg-white rounded-full h-8 w-8 flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-secondary-500">
                        <svg class="h-5 w-5" x-description="Heroicon name: solid/pencil" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        <div>
            <h5 class="font-medium text-gray-900">Url</h5>
            <div class="mt-2 flex items-center justify-between">
                <p class="text-xs text-gray-500 italic flex space-x-1 items-center">
                    <x-svg.external-link class="w-5 h-5"/>
                    <a tooltip="View file's direct link" href="{{ $selected_file->url() }}" class="hover:text-secondary-500 break-all" target="_blank">{{ $selected_file->url() }}</a>
                </p>
            </div>
        </div>
        <div class="flex justify-between">
            <a class="bg-secondary-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500" href="{{ $selected_file->url() }}" download="{{ $selected_file->src }}">
                Download
            </a>
            <x-modal
                wire:click="removeFile({{ $selected_file->id }})"
                triggerClass="ml-3 bg-white py-2 px-4 border border-gray-200 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                modalTitle="Delete File"
                triggerText="Delete"
            >
                Are you sure you want to delete this file?<br> <strong>{{ $selected_file->src }}</strong>
                @if($selected_file->resources()->count() > 0)
                    <div class="rounded-md p-3 bg-gray-100 mt-4">
                        <strong>These resources will also be deleted:</strong>
                        <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                            @foreach($selected_file->resources as $r)
                                <li class="w-full">
                                    <span class="w-full flex justify-between items-center space-x-2">
                                        <span tooltip="Resource" class="flex space-x-2 items-center">
                                            <x-svg.resource class="h-3 w-3"/><span>{{ $r->label }}</span>
                                        </span>
                                        <span tooltip="Resource's Client" class="rounded-full bg-amber-100 text-amber-500 px-2 py-1 flex space-x-2 items-center">
                                            <x-svg.client class="h-3 w-3"/><span>{{ $r->client->title }}</span>
                                        </span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </x-modal>
        </div>
    </div>
</aside>
