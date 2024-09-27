<x-slideout.wrapper>
    <x-button
        type="button"
        btn="secondary"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Create a new Phase"
        tooltip-p="left"
    >
        <x-svg.plus-circle class="h-5 w-5 text-white"/>
    </x-button>
    <form wire:submit.prevent="createPhase" autocomplete="off">
        <x-slideout
            title="Create a new Phase"
            subtitle="{{ $project->title }}"
            saveBtn="Create Phase"
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
    </form>
</x-slideout.wrapper>
