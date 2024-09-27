<div class="relative rounded-lg border border-gray-200 bg-white pr-6 pl-8 py-5 shadow-sm">
    <!-- Card Icon -->
    <div class="rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center absolute h-8 w-8 text-center" style="top:50%; left:0px; transform:translate(-50%, -50%);">
        <x-svg.package  class="h-4 w-4 text-gray-500"/>
    </div>
    <div class="flex items-center space-x-3">
        <div class="flex-1 min-w-0 space-y-2">
            @if($showService)
                <p tooltip="Package's Service" class="text-xs text-left text-gray-500 truncate inline-flex items-center">
                    <x-dynamic-component :component="'svg.service.'.$package->service->slug" class="h-6 w-6 mr-2 text-gray-300"/>
                    {{ $package->service->title }}
                </p>
            @endif
            <a tooltip="View Package" href="{{ route('admin.packages.show', $package->id) }}" class="focus:outline-none">
                <p class="flex space-x-2 items-center text-lg font-medium text-gray-900 transition-all hover:text-secondary-500">
                    <span>{{ $package->title }}</span>
                </p>
            </a>
            <p class="">{{ $package->description }}</p>
            <div class="flex space-x-3">
                @if($package->questions->count() > 0)
                    <span tooltip="Questions" class="text-gray-500 bg-gray-100 py-0.5 text-xs px-2 rounded-full flex space-x-2 items-center">
                        <x-svg.question class="w-4 h-4 text-gray-500"/>
                        <span>{{ $package->questions->count() }}</span>
                    </span>
                @endif
                @if($package->tutorials->count() > 0)
                    <span tooltip="Tutorials" class="text-gray-500 bg-gray-100 py-0.5 text-xs px-2 rounded-full flex space-x-2 items-center">
                        <x-svg.tutorial class="w-4 h-4 text-gray-500"/>
                        <span>{{ $package->tutorials->count() }}</span>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <x-menu.three-dot :showMenuContent="$showMenuContent">
        @if($showMenuContent)
            @livewire('admin.packages.edit', ['package' => $package, 'button_type' => 'inline-menu'], key('package-edit-' . $package->id))
            <x-modal
                wire:click="destroy"
                triggerText="Delete Package"
                triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                modalTitle="Delete Package"
            >
                Are you sure you want to delete this Package?<br><br>
                <strong>{{ $package->title }}</strong><br><br>
                <small>Tutorials and Questions created for this package will not be deleted.</small>
            </x-modal>
        @else
            <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
        @endif
    </x-menu.three-dot>
</div>
