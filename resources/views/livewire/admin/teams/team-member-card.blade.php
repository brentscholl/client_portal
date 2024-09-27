<div class="col-span-1 bg-white rounded-lg border relative">
    <div class="divide-y divide-gray-200">
        <div class="flex-1 flex items-center justify-between relative">
            <a tooltip="View User" href="{{ route('admin.users.show', $user->id) }}"
                class="group w-full flex items-center p-6 space-x-6">

                <img class="w-10 h-10 bg-gray-300 rounded-full flex-shrink-0"
                    src="{{ $user->avatarUrl() }}" alt="{{ $user->full_name }} profile picture">
                <div class="flex-1 truncate">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-gray-900 text-sm font-medium truncate transition-all group-hover:text-secondary-500">{{ $user->full_name }}</h3>
                    </div>
                    <p class="mt-1 text-gray-500 text-sm truncate">{{ $user->position }}</p>
                </div>

            </a>
        </div>
    </div>
    <x-menu.three-dot :showMenuContent="$showMenuContent">
        @if($showMenuContent)
            @livewire('admin.users.edit', ['user' => $user, 'button_type' => 'inline-menu'], key('user-edit-' . $user->id))
            <x-modal
                wire:click="unassign({{ $user->id }})"
                triggerText="Remove from Team"
                triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                modalTitle="Remove from Team"
                submitText="Remove"
            >
                Are you sure you want to remove <strong>{{ $user->full_name }}</strong> from {{ $team->title }} Team?
            </x-modal>
        @else
            <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
        @endif
    </x-menu.three-dot>
</div>
