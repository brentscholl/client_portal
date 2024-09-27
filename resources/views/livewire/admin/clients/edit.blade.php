<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit Client
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="saveClient" autocomplete="off">
        <x-slideout
            title="Editing Client"
            subtitle="{{ $title }}"
            saveBtn="Update Client"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5 text-left">
                @if($slideout_open)
                    <div>
                        <x-input.text
                            wire:model.lazy="title"
                            label="Company name"
                            :required="true"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.lazy="website_url"
                            label="Website URL"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.lazy="monthly_budget"
                            label="Monthly Budget"
                            type="number"
                            lead="$"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.lazy="annual_budget"
                            label="Annual Budget"
                            type="number"
                            lead="$"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        @if($client->avatar)
                            <label>
                                Photo
                            </label>
                            <div class="mt-2 flex items-center space-x-5">
                                <img
                                    class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100"
                                    src="{{ $client->avatarUrl() }}" alt="Client Image">
                                <button type="button" wire:click="removeAvatar()"
                                    class="bg-white py-2 px-3 border border-gray-200 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500">
                                    Remove
                                </button>
                            </div>
                        @else
                            <x-input.file-upload
                                wire:model="newAvatar"
                                label="Logo"
                                :required="false"
                                placeholder=""
                                multiple
                                uploader_type="image"
                                class=""/>
                        @endif
                    </div>
                @else
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.image/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
