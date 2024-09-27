<div class="{{ $simple ? 'mt-10' : '' }}">
    @if(!$simple)
    <div class="grid grid-cols-12 mb-4">
        <div class="col-span-1 pr-2">
            <label for="location" class="block text-sm leading-5 font-medium text-gray-700">Per Page</label>
            <select wire:model="perPage" id="location"
                class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-200 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                <option>20</option>
                <option>50</option>
                <option>100</option>
            </select>
        </div>

        <div class="col-span-11">
            <x-input.text wire:model="search" label="Search" placeholder="Search Clients..."/>
        </div>
    </div>
    @endif
    <div class="flex flex-col">
        <div class="-my-2 pt-2 pb-8 overflow-x-auto overflow-y-overflow sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow sm:rounded-lg border-b border-gray-200">
                <table class="s-table">
                    <thead>
                    <tr>
                        @if(!$simple)
                            <th></th>
                            <th scope="col">
                                <button wire:click="sortBy('title')"
                                    class="group flex items-center space-x-2 text-xs font-medium uppercase tracking-wider {{ $sortField == 'title' ? 'text-gray-900 underline' : 'text-gray-500' }} hover:text-gray-900 ">
                                    <span>Name</span>
                                    @if($sortField == 'title')
                                        @if($sortAsc)
                                            <x-svg.sort-desc class="h-3 w-3"/>
                                        @else
                                            <x-svg.sort-asc class="h-3 w-3"/>
                                        @endif
                                    @else
                                        <x-svg.sort class="opacity-60 group-hover:opacity-100 h-3 w-3"/>
                                    @endif
                                </button>
                            </th>
                            <th scope="col">Budget</th>
                            <th scope="col">Website URL</th>
                            <th scope="col">Primary Contact</th>
                            <th scope="col"><span class="sr-only">Edit</span></th>
                        @else
                            <th></th>
                            <th scope="col">
                                <button wire:click="sortBy('title')"
                                    class="group flex items-center space-x-2 text-xs font-medium uppercase tracking-wider {{ $sortField == 'title' ? 'text-gray-900 underline' : 'text-gray-500' }} hover:text-gray-900 ">
                                    <span>Client Name</span>
                                    @if($sortField == 'title')
                                        @if($sortAsc)
                                            <x-svg.sort-desc class="h-3 w-3"/>
                                        @else
                                            <x-svg.sort-asc class="h-3 w-3"/>
                                        @endif
                                    @else
                                        <x-svg.sort class="opacity-60 group-hover:opacity-100 h-3 w-3"/>
                                    @endif
                                </button>
                            </th>
                            <th scope="col">Primary Contact</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        @livewire('admin.clients.client-row', ['client' => $client, 'simple' => $simple], key('client-row-' . $client->id))
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $clients->links() }}
    </div>
</div>
