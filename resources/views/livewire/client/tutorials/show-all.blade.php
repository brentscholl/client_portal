<div>
    @if($tutorials->count() > 0)

        <div class="flex flex-col items-center w-full space-y-6 max-w-2xl w-full mx-auto mt-6">
            @foreach($tutorials as $tutorial)
                @livewire('client.tutorials.tutorial-card', ['tutorial' => $tutorial], key('tutorial-' . $tutorial->id))
            @endforeach
        </div>
        @if($tutorials->hasMorePages())
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
                            <svg class="-ml-1.5 mr-1 h-3 w-3 text-gray-400" x-description="Heroicon name: solid/plus-sm"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Show More</span>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <span class="relative block border-2 border-gray-200 border-dashed rounded-lg p-12 text-center max-w-7xl w-full mx-auto mt-6">
            <x-svg.tutorial class="mx-auto h-12 w-12 text-gray-200"/>
            <span class="mt-2 block text-sm font-medium text-gray-300">
              No Tutorials Available
            </span>
        </span>
    @endif
</div>
