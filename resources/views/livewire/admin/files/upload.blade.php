<x-slideout.wrapper>
    <x-button
        btn="{{ $button_type }}"
        @click="open = true"
        wire:click="openSlideout"
        tooltip="Upload a new File" tooltip-p="left"
    >
        @if($location == 'file-index')
            <x-svg.upload class="h-5 w-5 mr-2"/><span>Upload Files</span>
        @else
            @if($button_type == 'secondary' || $button_type == 'primary' || $button_type == 'edit')
                <x-svg.plus-circle class="h-5 w-5"/>
            @else
                Upload Files
            @endif
        @endif

    </x-button>
    <form wire:submit.prevent="uploadFiles" autocomplete="off">
        <x-slideout
            title="Upload Files"
            subtitle=""
            saveBtn="Upload"
        >
            @if($errors->all())
                <ul>
                    @foreach($errors->all() as $message)
                        <li class="text-red-500">{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
            <div class="space-y-6 pt-6 pb-5">
                @if($slideout_open)
                    <div>
                        <x-input.file-upload
                            wire:key="{{ now() }}"
                            wire:model="files"
                            label="Upload Files"
                            :required="false"
                            placeholder=""
                            multiple
                            uploader_type="file"
                            class=""/>
                    </div>
                @else
                    <x-skeleton.input.textarea/>
                @endif
            </div>
        </x-slideout>
    </form>
</x-slideout.wrapper>

