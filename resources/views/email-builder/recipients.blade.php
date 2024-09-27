<div class="flex space-x-2 items-center">
    <div>To:</div>
    <div class="flex flex-wrap items-center -mb-3">
        <div class="flex flex-wrap items-center">
            @foreach($recipients as $recipient)
                <div class="mr-3 mb-3 relative flex items-center space-x-3 text-gray-300 pl-3 pr-4 py-1 rounded-full bg-gray-100" wire:key="recipient_{{ $recipient->id }}">
                    <div class="flex-shrink-0">
                        <img class="h-6 w-6 rounded-full" src="{{ $recipient->avatarUrl() }}" alt="{{ $recipient->fullname }}">
                    </div>
                    <div>
                        <div class="text-sm font-medium transition text-gray-900">{{ $recipient->fullname }}</div>
                        <div class="text-xs font-medium text-gray-600">{{ $recipient->position }}</div>
                    </div>
                    @if($recipient->id != $client->primary_contact)
                        <button
                            type="button"
                            title="Remove"
                            wire:click="unassignRecipient({{ $recipient->id }})"
                            class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                            style="top: -0.1rem; right: -0.3rem;">
                            <x-svg.x/>
                        </button>
                    @else
                        <span
                            class="absolute h-4 w-4 p-0.5 rounded-full bg-green-100 text-green-800 opacity-100 focus:outline-none"
                            style="top: -0.1rem; right: -0.3rem;"
                        >
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                    </svg>
                                                                </span>
                    @endif
                </div>
            @endforeach
            <div>
                <x-modal
                    type="add"
                    cancelText="Cancel"
                    modalTitle="Add Recipient"
                    :showSubmitBtn="false"
                    wire:click="openRecipientModal"
                    :load="true"
                >
                    <x-slot name="customTrigger">
                        <button
                            @click="open = true"
                            type="button"
                            class="mb-3 rounded-full flex justify-center items-center w-8 h-8 border text-gray-300 hover:border-secondary-500 hover:text-secondary-500 transition-all durration-100 ease-in-out"
                            wire:click="openRecipientModal"
                        >
                            <x-svg.plus class="h-4 w-4"/>
                        </button>
                    </x-slot>
                    <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
                        @if($recipientModalOpen)
                            <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{now()}}">
                                <li class="py-4 flex items-center justify-between w-full">
                                    <label
                                        for="all"
                                        class="flex items-center cursor-pointer"
                                    >
                                        <div class="h-10 w-10 bg-secondary-100 text-secondary-500 rounded-full flex justify-center items-center">
                                            <x-svg.team class="h-5 w-5"/>
                                        </div>
                                        <div class="ml-3">
                                            <p class="font-medium text-gray-900">All Representatives</p>
                                        </div>
                                    </label>
                                    <div>
                                        <div x-data="{ loading: false }">
                                            <button
                                                wire:click="assignAllRecipient"
                                                type="button"
                                                @click="loading = true"
                                                x-bind:disabled="loading"
                                                class="rounded-full border border-secondary-500 bg-white text-secondary-500 p-2 flex items-center justify-center h-8 w-8 hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                            >
                                                <span x-cloak x-show="loading == false">
                                                    <x-svg.plus class="w-5 h-5"/>
                                                </span>
                                                <span x-show="loading == true">
                                                    <x-svg.spinner class="w-5 h-5"/>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                                @forelse($assignable_recipients as $recipient)
                                    <li class="py-4 flex items-center justify-between w-full">
                                        <label
                                            for="{{ $recipient->id }}"
                                            class="flex items-center cursor-pointer"
                                        >
                                            <img class="h-10 w-10 rounded-full"
                                                src="{{ $recipient->avatarUrl() }}"
                                                alt="{{ $recipient->fullname }}">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $recipient->fullname }}</p>
                                                <p class="text-sm text-gray-500">{{ $recipient->position }}</p>
                                            </div>
                                        </label>
                                        <div>
                                            @if(in_array($recipient->id, $recipient_ids))
                                                <div x-data="{ loading: false }">
                                                                                            <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                                                                                <x-svg.check class="w-5 h-5 inline-block"/>
                                                                                            </span>
                                                </div>
                                            @else
                                                <div x-data="{ loading: false }">
                                                    <button
                                                        wire:click="assignRecipient({{ $recipient }})"
                                                        type="button"
                                                        @click="loading = true"
                                                        x-bind:disabled="loading"
                                                        class="rounded-full border border-secondary-500 bg-white text-secondary-500 p-2 flex items-center justify-center h-8 w-8 hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
                                                    >
                                                                                                <span x-cloak x-show="loading == false">
                                                                                                    <x-svg.plus class="w-5 h-5"/>
                                                                                                </span>
                                                        <span x-show="loading == true">
                                                                                                    <x-svg.spinner class="w-5 h-5"/>
                                                                                                </span>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    No recipients available
                                @endforelse
                            </ul>
                        @else
                            <div class="pr-14 my-6 w-full">
                                <x-svg.spinner class="h-8 w-8 text-secondary-500 mx-auto"/>
                            </div>
                        @endif
                    </div>
                </x-modal>
            </div>
        </div>
    </div>

</div>
@error('recipients')
<p wire:key="error_recipients"
    class="form-input-container__input__error-message"
    id="error-recipients">
    {{ $message }}
</p>
@enderror
