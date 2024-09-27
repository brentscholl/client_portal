<div>
    @if($assign_marketing_advisor)
        <h2 class="text-sm font-medium text-gray-500">Marketing {{ Str::plural('Advisor', $assignees->count()) }}</h2>
    @else
        <h2 class="text-sm font-medium text-gray-500">{{ Str::plural('Assignee', $assignees->count()) }}</h2>
    @endif
    <ul class="mt-3 space-y-3">
        @forelse($assignees as $assignee)
            @php($is_primary_ma = false)
            @if($assign_marketing_advisor && $assignee->assigneeable_type == 'App\\Models\\Client')
                @php($is_primary_ma = true)
            @endif
            <li
                tooltip-p="left"
                @if($assignee->assigneeable_type == get_class($model))
                    @if($assign_marketing_advisor)
                        tooltip="This Marketing Advisor is assigned to the {{ str_replace('App\\Models\\', '',get_class($model)) }}"
                    @else
                        tooltip="This {{ $assignee->user->position }} is assigned to the {{ str_replace('App\\Models\\', '',get_class($model)) }}"
                    @endif
                @else
                    @if($assign_marketing_advisor)
                        tooltip="This Marketing Advisor is assigned to the {{ str_replace('App\\Models\\', '',get_class($model)) }}'s {{ str_replace('App\\Models\\', '', $assignee->assigneeable_type) }}"
                    @else
                        tooltip="This {{ $assignee->user->position }} is assigned to the {{ str_replace('App\\Models\\', '',get_class($model)) }}'s {{ str_replace('App\\Models\\', '', $assignee->assigneeable_type) }}"
                    @endif
                @endif
                class="flex justify-start group relative"
            >
                <a href="{{ route('admin.users.show', $assignee->user->id) }}" class="flex items-center space-x-3 text-gray-300">
                    <div class="flex-shrink-0 relative">
                        <img class="h-10 w-10 rounded-full" src="{{ $assignee->user->avatarUrl() }}" alt="{{ $assignee->user->fullname }}">
                        @if($is_primary_ma)
                            <span class="h-4 w-4 shadow rounded-full absolute flex-row justify-center items-center" style="bottom: -0.3rem; left: -0.3rem; background: rgba(255,255,255,0.8);">
                                <svg class="h-4 w-4 text-green-500 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="text-sm font-medium transition text-gray-900 hover:text-secondary-500">{{ $assignee->user->fullname }}</div>
                        <div class="text-xs font-medium text-gray-600">{{ $assignee->user->position }}</div>
                    </div>
                </a>
                @if($assignee->assigneeable_type == get_class($model))
                    <button
                        tooltip="Unassign User"
                        wire:click="unassign({{ $assignee->user->id }})"
                        class="absolute h-4 w-4 p-0.5 shadow rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                        style="top: -0.3rem; left: -0.3rem; ">
                        <x-svg.x/>
                    </button>
                @endif
            </li>
        @empty
            @if($assign_marketing_advisor)
                <p class="text-xs text-gray-300">No Marketing Advisor assigned</p>
            @else
                <p class="text-xs text-gray-300">No Assignee</p>
            @endif
        @endforelse
    </ul>
    @if($allow_assign)
        <x-modal
            type="add"
            cancelText="Done"
            triggerClass="flex text-xs text-gray-400 mt-4 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
            modalTitle="{{ $assign_marketing_advisor ? 'Add Marketing Advisor' : 'Add Assignee' }}"
            :showSubmitBtn="false"
            wire:click="load"
            :load="true"
        >
            <x-slot name="triggerText">
                <x-svg.plus-circle class="h-4 w-4 mr-2"/>
                @if($assign_marketing_advisor)
                    Add Marketing Advisor
                @else
                    Add Assignee
                @endif
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
    @endif
</div>
