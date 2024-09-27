<aside class="hidden xl:block xl:pl-8">
    <div class="space-y-5">
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-gray-900 text-sm font-medium">Team Members: <strong>{{ $team->users->count() }}</strong></span>
        </div>

        <!-- Sidebar Item -->
{{--        <div class="flex items-center space-x-2">--}}
{{--            <x-svg.tutorial class="h-5 w-5 text-gray-400"/>--}}
{{--            <span class="text-gray-900 text-sm font-medium">Tutorials: <strong>{{ $team->tutorials->count() }}</strong></span>--}}
{{--        </div>--}}

        <!-- Sidebar Item -->
{{--        <div class="flex items-center space-x-2">--}}
{{--            <x-svg.client class="h-5 w-5 text-gray-400"/>--}}
{{--            <span class="text-gray-900 text-sm font-medium">Clients: <strong>{{ $clients->count() }}</strong></span>--}}
{{--        </div>--}}

    <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Team"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Team</span>
            </x-slot>
            Are you sure you want to delete this Team?
        </x-modal>
    </div>
</aside>
