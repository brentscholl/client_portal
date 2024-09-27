<x-slideout.wrapper
    class="col-span-1"
>
    @switch($button_type)
        @case('block')
            <button
                type="button"
                wire:click="openSlideout"
                @click="open = true"
                class="w-full h-full group flex flex-col space-y-3 justify-center items-center bg-white rounded-lg border border-gray-400 border-2 border-dashed relative hover:border-solid hover:border-secondary-500 hover:bg-secondary-100 s-transition hover:shadow-lg"
            >
                <div
                    class="flex justify-center items-center w-12 h-12 border border-dashed border-gray-400 border-2 rounded-full text-gray-400 group-hover:border-secondary-500 group-hover:text-secondary-500 s-transition">
                    <x-svg.plus class="h-8 w-8"/>
                </div>
                <span
                    class="text-sm font-medium text-gray-400 group-hover:text-secondary-500 s-transition">Add Team Member</span>
            </button>
        @break
        @case('inline-menu')
            <x-button
                btn="{{ $button_type }}"
                @click="open = true"
                wire:click="openSlideout"
            >
                Edit
            </x-button>
        @break
    @endswitch
    <form wire:submit.prevent="createUser" autocomplete="off">
        <x-slideup
            title="{{ $is_editing ? 'Update ' . $user->full_name : 'Create a new user' }}"
            saveBtn="{{ $is_editing ? 'Update User' : 'Create User' }}"
        >

            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="col-span-1">
                            <x-input.text
                                wire:model.defer="first_name"
                                label="First Name"
                                :required="true"
                                placeholder=""
                                class=""/>
                        </div>

                        <div class="col-span-1">
                            <x-input.text
                                wire:model.defer="last_name"
                                label="Last Name"
                                :required="true"
                                placeholder=""
                                class=""/>
                        </div>

                        <div class="col-span-1">
                            <x-input.text
                                wire:model.defer="email"
                                label="Email Address"
                                type="email"
                                :required="true"
                                placeholder=""
                                class=""/>
                        </div>
                        <div class="col-span-1">
                            <x-input.text
                                wire:model.defer="email_confirmation"
                                label="Confirm Email Address"
                                type="email"
                                :required="true"
                                placeholder=""
                                class=""/>
                        </div>

                        <div class="col-span-1">
                            <x-input.text
                                wire:model.defer="password"
                                :label="$is_editing ? 'Password <small>Leave blank to keep current password</small>' : 'Password'"
                                :required="!$is_editing"
                                placeholder=""
                                class=""/>
                        </div>

                        <div class="col-span-1">
                            <x-input.text
                                wire:model.lazy="phone"
                                label="Phone Number"
                                :required="false"
                                placeholder=""
                                class=""/>
                        </div>
                        <div class="col-span-1">
                            <x-input.text
                                wire:model.defer="position"
                                label="Position"
                                :required="false"
                                placeholder=""
                                class=""/>
                        </div>
                        @if(!$is_editing)
                            <div class="col-span-1 pt-6">
                                <x-input.checkbox
                                    wire:model.defer="email_new_user"
                                    label="Send an email to the new user with their new account details"
                                    class=""/>
                            </div>
                        @endif
                    </div>

                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.text/>
                        </div>
                        <div class="col-span-1">
                            <x-skeleton.input.checkbox/>
                        </div>
                    </div>
                @endif
            </div>
        </x-slideup>
    </form>
</x-slideout.wrapper>
