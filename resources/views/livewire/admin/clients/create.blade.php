<x-slideout.wrapper>
    <x-button
        type="button"
        btn="secondary"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Client"
        tooltip-p="left"
    >
        <x-svg.plus-circle class="h-5 w-5 text-white"/>
    </x-button>
    <form wire:submit.prevent="addClient" autocomplete="off">
        <x-slideout
            title="Create a new Client"
            subtitle="Create the client then add users to it."
            saveBtn="Create Client"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    <div>
                        <x-input.text
                            wire:model.defer="title"
                            label="Company name"
                            :required="true"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="website_url"
                            label="Website URL"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="monthly_budget"
                            label="Monthly Budget"
                            type="number"
                            lead="$"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.text
                            wire:model.defer="annual_budget"
                            label="Annual Budget"
                            type="number"
                            lead="$"
                            :required="false"
                            placeholder=""
                            class=""/>
                    </div>

                    <div>
                        <x-input.file-upload
                            wire:key="{{ now() }}"
                            wire:model="newAvatar"
                            label="Logo"
                            :required="false"
                            placeholder=""
                            multiple
                            uploader_type="image"
                            class=""/>
                    </div>
                @else
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.text/>
                    <x-skeleton.input.textarea/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
