<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
    >
        @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
            <x-svg.edit class="-ml-1 mr-2 h-5 w-5"/>
            Edit package
        @else
            Edit
        @endif
    </x-button>
    <form wire:submit.prevent="savePackage" autocomplete="off">
        <x-slideout
            title="Edit Package"
            subtitle="{{ $package->title }}"
            saveBtn="Update Package"
        >
            @include('errors.list')
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)

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

                @else
                    <x-skeleton.input.text/>
                    <x-skeleton.input.textarea/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>
