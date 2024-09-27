<div wire:key="sent-history">
    <div class="px-3 py-6 rounded-b-md rounded-tr-md bg-gray-50">
        <div class="flow-root">
            <ul role="list" class="-mb-8">
                @foreach($emails as $email)
                    @livewire('admin.emails.history-item', ['email' => $email, 'loop_last' => $loop->last], key('history_'. $email->id))
                @endforeach
            </ul>
        </div>
    </div>
    @if($emails->hasMorePages())
        <div class="max-w-2xl mx-auto px-4 mb-4">
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
</div>
