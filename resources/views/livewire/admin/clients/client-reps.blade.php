<section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
    <x-card opened="{{ $cardOpened }}">
        <x-card.header title="Representatives" count="{{ $reps->count() }}">
            @livewire('admin.users.create', ['client' => $client, 'setClient' => false ], key('create-rep'))
        </x-card.header>
        <x-card.body>
            @if($reps->count() > 0)
                @if(!$client->primary_contact)
                    <div class="text-center">
                        <div class="bg-red-100 py-1 px-3 text-red-500 rounded-full mx-auto mb-6 inline-block text-sm">
                            No Primary Contact set. Please set a Primary Contact.
                        </div>
                    </div>
                @endif
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($reps as $user)
                        @livewire('admin.clients.rep-card', ['user' => $user, 'client' => $client], key($user->id))
                    @endforeach
                </div>
                @if($reps->hasMorePages())
                    <div class="max-w-2xl mx-auto px-4 mb-4 mt-4">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <button wire:click="loadMore()"
                                    type="button"
                                    class="inline-flex items-center shadow-sm px-2 py-0.5 border border-gray-200 text-xs font-medium rounded-full text-gray-400 bg-white hover:bg-secondary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                >
                                    <svg class="-ml-1.5 mr-1 h-3 w-3 text-gray-400" x-description="Heroicon name: solid/plus-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Show More</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <span class="relative block w-full border-2 border-gray-200 border-dashed rounded-lg p-12 text-center">
                    <x-svg.user class="mx-auto h-12 w-12 text-gray-200"/>
                    <span class="mt-2 block text-sm font-medium text-gray-300">
                      No client representatives assigned
                    </span>
                </span>
            @endif
        </x-card.body>
    </x-card>
</section>
