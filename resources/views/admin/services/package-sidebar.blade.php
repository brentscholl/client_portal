<aside class="hidden xl:block xl:pl-8">
    <div class="space-y-5">
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <x-svg.package class="h-5 w-5 text-gray-400"/>
            <span class="text-gray-900 text-sm font-medium">Questions: <strong>{{ $package->questions->count() }}</strong></span>
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <x-svg.tutorial class="h-5 w-5 text-gray-400"/>
            <span class="text-gray-900 text-sm font-medium">Tutorials: <strong>{{ $package->tutorials->count() }}</strong></span>
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <x-svg.client class="h-5 w-5 text-gray-400"/>
            <span class="text-gray-900 text-sm font-medium">Clients: <strong>{{ $clients->count() }}</strong></span>
        </div>

        <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Package"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Package</span>
            </x-slot>
            Are you sure you want to delete this Package?<br><br>
            <small>Tutorials and Questions created for this package will not be deleted.</small>
        </x-modal>
    </div>
</aside>
