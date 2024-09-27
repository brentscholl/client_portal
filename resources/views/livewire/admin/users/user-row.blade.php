<tr>
    <td>
        <div class="flex items-center">
            <a tooltip="View User" href="{{ route('admin.users.show', $user->id) }}" class="flex-shrink-0 h-10 w-10">
                <img class="h-10 w-10 rounded-full"
                    src="{{$user->avatarUrl()}}"
                    alt="">
            </a>
            <div class="ml-4">
                <div>
                    <a tooltip="View User" href="{{ route('admin.users.show', $user->id) }}" class="text-sm leading-5 font-medium text-gray-900 hover:text-secondary-500">{{$user->first_name}} {{ $user->last_name }}</a>
                    @if($user->id != auth()->user()->id)
                    <a tooltip="Sign in to this user's account" wire:click="impersonate({{$user->id}})" href="#" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-secondary-100 text-secondary-500 ml-1 transition duration-300 ease-in-out hover:bg-secondary-600 hover:text-white">Impersonate</a>
                    @endif
                </div>
                <div class="text-sm leading-5 text-gray-500 hover:text-gray-600 transition duration-300 ease-in-out">
                    <a tooltip="Email User" href="mailto:{{$user->email}}">{{$user->email}}</a> @if($user->phone) |
                    <a tooltip="Call User" class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="tel:{{$user->phone}}">{{ $user->phone }}</a> @endif
                </div>
            </div>
        </div>
    </td>
    <td>
        @if($user->client)
            <div class="text-sm text-gray-900 hover:text-secondary-500">
                <a tooltip="View User's Client" href="{{ route('admin.clients.show', $user->client->id) }}">{{ optional($user->client)->title }}</a>
            </div>
        @endif
    </td>
    <td>
        <div class="text-sm text-gray-500">{{ $user->position }}</div>
    </td>
    <td>
        @if($user->client_id)
            <span tooltip="This user only has client level privileges"
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                        Client
                                    </span>
        @else
            <span tooltip="This user has full admin privileges"
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Admin
                                    </span>
        @endif
    </td>
    <td>
        @if( $user->teams->count() > 0 )
            <ul class="flex space-x-2">
                @foreach($user->teams as $team)
                    <li>
                        <a tooltip="View Team" href="{{ route('admin.teams.show', $team->id) }}" class=" group rounded-full bg-secondary-100 text-xs text-secondary-500 py-1 px-4 flex space-x-2 transition-all ease-in-out duration-100 hover:bg-secondary-500 hover:text-white">
                            <span class="text-secondary-500 text-xs font-medium group-hover:text-white transition-all ease-in-out duration-100">{{ $team->title }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </td>
    <td class="text-right leading-5 font-medium">
        <x-menu.three-dot :inline="true">
            @if($showMenuContent)

            <div>
                @livewire('admin.users.edit', ['user' => $user, 'button_type' => 'inline-menu'], key('user-edit-'. $user->id))
                @if($user->id !== auth()->user()->id)
                    <x-modal
                        wire:click="destroy()"
                        triggerText="Delete User"
                        triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                        modalTitle="Delete User"
                    >
                        Are you sure you want to delete this <strong>{{ $user->full_name }}</strong>?<br>
                    </x-modal>
                @endif
            </div>
            @else
                <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
            @endif
        </x-menu.three-dot>
    </td>
</tr>
