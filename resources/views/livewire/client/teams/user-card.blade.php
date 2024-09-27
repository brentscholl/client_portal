<div class="col-span-1 bg-white {{ $user->id == auth()->user()->client->primary_contact ? 'border border-green-100' : '' }} rounded-lg border relative">
    @if($user->id == auth()->user()->client->primary_contact)
        <span
            class="absolute flex space-x-2 left-4 flex-shrink-0 inline-block px-2 py-0.5 text-green-800 text-xs font-medium bg-green-100 rounded-full"
            style="top:-1em;"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
            <span>Primary Contact</span>
        </span>
    @endif
    <div class="divide-y divide-gray-200">
        <div class="flex-1 flex items-center justify-between relative">
            <span class="group w-full flex items-center justify-between p-6 space-x-6">
                <div class="flex-1 truncate">
                    <div class="flex items-center space-x-3">
                        <h3 class="text-gray-900 text-sm font-medium truncate">{{ $user->full_name }}</h3>
                    </div>
                    <p class="mt-1 text-gray-500 text-sm truncate">{{ $user->position }}</p>
                </div>
                <img class="w-10 h-10 bg-gray-300 rounded-full flex-shrink-0"
                    src="{{ $user->avatarUrl() }}" alt="{{ $user->full_name }} profile picture">
            </span>
        </div>
        <div>
            <div class="-mt-px flex flex-col divide-y divide-gray-200">
                <div class="w-full">
                    <a tooltip="Send User and email" href="mailto:{{ $user->email }}"
                        class="relative w-full inline-flex items-center py-4 px-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                        <svg class="w-5 h-5 text-gray-400"
                            x-description="Heroicon name: mail"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path
                                d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path
                                d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        <span class="ml-3">{{ $user->email }}</span>
                    </a>
                </div>
                <div class="w-full">
                    <a tooltip="Call User" href="tel:{{ $user->phone }}"
                        class="relative w-full inline-flex items-center py-4 px-4 text-sm text-gray-700 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                        <svg class="w-5 h-5 text-gray-400"
                            x-description="Heroicon name: phone"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path
                                d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                        <span class="ml-3">{{ $user->phone }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <x-menu.three-dot :showMenuContent="$showMenuContent">
        @if($showMenuContent)
            @livewire('client.teams.create', ['is_editing' => true, 'user' => $user, 'button_type' => 'inline-menu'], key('user-edit-' . $user->id))
            @if(auth()->user()->client->primary_contact != $user->id)
                <x-button
                    btn="inline-menu"
                    wire:click="makePrimaryContact"
                >
                    Make primary contact
                </x-button>
            @endif
            <x-modal
                wire:click="destroy"
                triggerText="Delete User"
                triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                modalTitle="Delete User"
            >
                Are you sure you want to delete <strong>{{ $user->full_name }}</strong>?
            </x-modal>
        @else
            <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
        @endif
    </x-menu.three-dot>
</div>
