<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit phase
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="savePhase" autocomplete="off">
        <x-slideout
            title="Edit Phase"
            subtitle="{{ $phase->title }}"
            saveBtn="Update Phase"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                <div>
                    <x-input.text
                        wire:model.defer="title"
                        label="Title"
                        :required="true"
                        placeholder=""
                        class=""
                    />
                </div>

                <div>
                    <x-input.textarea
                        wire:model.defer="description"
                        label="Description"
                        placeholder=""
                        class=""
                    />
                </div>

                <div>
                    <x-input.date
                        wire:model.defer="due_date"
                        label="Due Date"
                        :required="false"
                        placeholder=""
                        class=""
                    />
                </div>

                <x-labeled-divider label="Phase Add-ons"/>

                <x-input.urls :urls="$urls"/>
            </div>
        </x-slideout>
        <x-
    </form>
</x-slideout.wrapper>

