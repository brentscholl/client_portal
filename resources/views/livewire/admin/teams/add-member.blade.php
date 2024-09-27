<x-modal
    type="add"
    cancelText="Done"
    triggerClass="group inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white gradient-secondary transition-all duration-300 ease-in-out hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
    modalTitle="Add Team Members"
    :showSubmitBtn="false"
    wire:click="load"
    :load="true"
>
    <x-slot name="triggerText">
        <span tooltip="Add Team Members" tooltip-p="left"><x-svg.plus-circle class="h-5 w-5 text-white"/></span>
    </x-slot>
    <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
        @if($modalOpen)
            <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{now()}}">
                @forelse($assignable_users as $user)
                    <li class="py-4 flex items-center justify-between w-full">
                        <label
                            for="{{ $user->id }}"
                            class="flex items-center cursor-pointer"
                        >
                            <img class="h-10 w-10 rounded-full"
                                src="{{ $user->avatarUrl() }}"
                                alt="{{ $user->fullname }}">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $user->fullname }}</p>
                                <p class="text-sm text-gray-500">{{ $user->position }}</p>
                            </div>
                        </label>
                        <div>
                            @if(in_array($user->id, $assignee_ids))
                                <div x-data="{ loading: false }">
                                    <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                        <x-svg.check class="w-5 h-5 inline-block"/>
                                    </span>
                                </div>
                            @else
                                <div x-data="{ loading: false }">
                                    <button
                                        wire:click="assign({{ $user->id }})"
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
                    No users available
                @endforelse
            </ul>
        @else
            <div class="pr-14 my-6 w-full">
                <x-svg.spinner class="h-8 w-8 text-secondary-500 mx-auto"/>
            </div>
        @endif
    </div>
</x-modal>
