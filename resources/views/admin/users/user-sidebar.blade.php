<aside class="hidden xl:block xl:pl-8">
    <div class="space-y-5">
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            @if($user->archived)
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <span class="text-green-700 text-sm font-medium">Archived</span>
            @endif
        </div>
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <a tooltip="Sign in as this User" tooltip-p="left" wire:click="impersonate({{$user->id}})" href="#"
                class="px-3 py-1 border border-secondary-500 inline-flex text-sm leading-5 font-semibold rounded-full bg-secondary-100 text-secondary-500 ml-1 transition duration-300 ease-in-out hover:bg-secondary-600 hover:text-white shadow">Impersonate</a>
        </div>

        <!-- Sidebar Item -->
        @if($user->teams->count() > 0)
            <div class="flex items-center space-x-2">
                <x-svg.team class="h-5 w-5 text-gray-400"/>
                <span class="text-gray-900 text-sm font-medium">Assigned to {{ Str::plural('Team', $user->teams->count()) }}:</span>
            </div>
            <div class="">
                <ul class="mt-3 ml-6 space-y-3">
                    @foreach($user->teams as $team)
                        <li class="flex justify-start group relative">
                            <a tooltip="View Team" href="{{ route('admin.teams.show', $team->id) }}"
                                class="flex items-center space-x-2 text-gray-300 py-0.5 px-3 rounded-full bg-gray-200">
                                <div
                                    class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $team->title }}</div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif


    <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: calendar"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-900 text-sm font-medium">Created on <time
                    datetime="2020-12-02">{{ date_format($user->created_at, 'M d, Y @ h:ia') }}</time></span>
        </div>

        <!-- Sidebar Item -->
        @if($user->id !== auth()->user()->id)
            <x-modal
                wire:click="destroy"
                triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
                modalTitle="Delete User"
            >
                <x-slot name="triggerText">
                    <x-svg.trash class="h-5 w-5"/>
                    <span class="text-sm font-medium">Delete User</span>
                </x-slot>
                Are you sure you want to delete this User?<br>
            </x-modal>
        @endif
    </div>
</aside>
