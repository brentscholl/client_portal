<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit User
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="saveUser" autocomplete="off">
        <x-slideout
            title="Editing User"
            subtitle="{{ optional($user)->fullname }}"
            saveBtn="Update User"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5 text-left">
                @if($slideout_open)

                    <div>
                        <x-input.text
                            wire:model.defer="first_name"
                            label="First Name"
                            :required="true"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="last_name"
                            label="Last Name"
                            :required="true"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="email"
                            label="Email Address"
                            type="email"
                            :required="true"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="password"
                            label="Password <small>Leave blank to keep current password</small>"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="phone"
                            label="Phone Number"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="position"
                            label="Position"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div x-data="{ showInput: @entangle('user_type') }">
                        @switch($user_type)
                            @case('basic')
                            @php($type_index = 0)
                            @break
                            @case('admin')
                            @php($type_index = 1)
                            @break
                        @endswitch
                        <x-input.radio-set index="{{ $type_index }}" label="User Type">
                            <x-input.radio
                                wire:model="user_type"
                                value="basic"
                                label="Basic"
                                description="User belongs to client. Does not have access to Admin Dashboard"
                                :first="true"
                                x-model="showInput"
                            />
                            <x-input.radio
                                wire:model="user_type"
                                value="admin"
                                label="Admin"
                                description="User has access to Admin Dashboard."
                                :last="true"
                            />
                        </x-input.radio-set>

                        <div x-show="showInput == 'basic'">
                            <div class="mt-6">
                                <x-input.client-select
                                    :client="$client"
                                    :clients="$clients"
                                    :required="true"
                                />
                            </div>
                        </div>

                        <div x-show="showInput == 'admin'">
                            <div class="mt-6">
                                <label id="client-select-label-{{ rand() }}" class="mt-3">
                                    Teams:
                                </label>
                                <ul class="mt-3 flex flex-wrap items-center">
                                    @if($assignable_teams)
                                        @php($a_i = 0)
                                        @foreach($assignable_teams as $assignable_team)
                                            @if(in_array($assignable_team->id, $team_ids))
                                                <li class="flex justify-start group relative py-0.5 px-3 leading-4 rounded-full bg-gray-200 mb-3 mr-3">
                                                    <div class="text-sm font-medium transition text-gray-900">{{ $assignable_team->title }}</div>
                                                    <button
                                                        type="button"
                                                        title="Remove"
                                                        wire:click="unassignTeam({{ $assignable_team->id }})"
                                                        class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                                                        style="top: -0.3rem; right: -0.3rem;">
                                                        <x-svg.x/>
                                                    </button>
                                                </li>
                                                @php($a_i++)
                                            @endif
                                        @endforeach
                                        @if($a_i == 0)
                                            <p class="text-xs text-gray-300 mb-2">No teams assigned</p>
                                        @endif
                                    @else
                                        <p class="text-xs text-gray-300 mb-2">No teams assigned</p>
                                    @endif
                                </ul>
                                <x-modal
                                    type="add"
                                    cancelText="Done"
                                    triggerClass="flex text-xs text-gray-400 mt-2 rounded-md border border-gray-200 px-2 py-1 hover:text-secondary-500 hover:border-secondary-500"
                                    modalTitle="Add Team"
                                    :showSubmitBtn="false"
                                    wire:click="load"
                                >
                                    <x-slot name="triggerText">
                                        <x-svg.plus-circle class="h-4 w-4 mr-2"/>
                                        Add Team
                                    </x-slot>
                                    <div class="w-full mx-auto py-6 px-4 sm:px-0 md:py-7">
                                        <ul role="list" class="divide-y divide-gray-200 mr-10" id="{{ now() }}">
                                            @forelse( $assignable_teams ?: [] as $assignable_team )
                                                <li class="py-4 flex items-center justify-between w-full">
                                                    <label
                                                        for="{{ $assignable_team->id }}"
                                                        class="flex items-center cursor-pointer"
                                                    >
                                                        <p class="text-sm font-medium text-gray-900">{{ $assignable_team->title }}</p>
                                                    </label>
                                                    <div>
                                                        @if(in_array($assignable_team->id, $team_ids))
                                                            <div x-data="{ loading: false }">
                                                                <span class="group rounded-full border border-green-500 bg-green-500 text-white p-2 flex items-center justify-center h-8 w-8">
                                                                    <x-svg.check class="w-5 h-5 inline-block"/>
                                                                </span>
                                                            </div>
                                                        @else
                                                            <div x-data="{ loading: false }">
                                                                <button
                                                                    wire:click="assignTeam({{ $assignable_team->id }})"
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
                                                No teams available
                                            @endforelse
                                        </ul>
                                    </div>
                                </x-modal>
                            </div>
                        </div>
                    </div>
                @else
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.radio-set>
                        <x-skeleton.input.radio :first="true"/>
                        <x-skeleton.input.radio :last="true"/>
                    </x-skeleton.input.radio-set>
                    <x-skeleton.input.text/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>

