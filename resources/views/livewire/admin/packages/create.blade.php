<x-slideout.wrapper>
    <x-button
        type="button"
        btn="secondary"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Package"
        tooltip-p="left"
    >
        <x-svg.plus-circle class="h-5 w-5 text-white"/>
    </x-button>
    <form wire:submit.prevent="createPackage" autocomplete="off">
        <x-slideout
            title="Create a new Package"
            subtitle="{{ optional($service)->title ?  optional($service)->title : '' }}"
            saveBtn="Create Package"
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
