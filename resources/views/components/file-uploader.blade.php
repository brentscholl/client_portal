@props([
'label' => "",
'uploader_type' => '',
])
<div class="{{ $attributes->get('class') }}"
    wire:ignore
    wire:key="data_{{ $attributes->whereStartsWith('wire:model')->first() }}"
    x-data=""
    x-init="
            FilePond.registerPlugin(FilePondPluginImagePreview);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginImageResize);
            FilePond.registerPlugin(FilePondPluginFilePoster);
            FilePond.registerPlugin(FilePondPluginFileMetadata);

            FilePond.setOptions({
                allowMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
                itemInsertLocation: 'after',
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    },
                    load: (source, load, error, progress, abort, headers) => {
                        var myRequest = new Request(source);
                        fetch(myRequest).then(function(response) {
                          response.blob().then(function(myBlob) {
                            load(myBlob)
                          });
                        });
                    },
                    onremovefile(error, file) {
                        if (file.source === parseInt(file.source, 10)){
                            @this.removeFile(file.source);
                        }
                    },
                },
            });
        const pond = FilePond.create($refs.input, {
            @switch($uploader_type)
                @case('image')
                    acceptedFileTypes: ['image/png', 'image/jpeg'],
                    maxFileSize: '7MB',
                    allowImageResize: true,
                    imageResizeTargetWidth: '300px',
                    imageResizeTargetHeight: '300px',
                    filePosterMaxHeight: '256px',
                @break
                @case('file')
                        maxFileSize: '50MB',
                @break
            @endswitch
        });

    "
>
    <label for="{{ $attributes->whereStartsWith('wire:model')->first() }}">{{ $label }}</label>

    <div class="mt-1 relative rounded-md shadow-sm" >
        <input
            {{ $attributes->whereStartsWith('wire:model') }}
            id="{{ $attributes->whereStartsWith('wire:model')->first() }}"
            {{ isset($attributes['multiple']) ? 'multiple' : '' }}
            type="file" x-ref="input">
    </div>
    @error($attributes->whereStartsWith('wire:model')->first())
    <p wire:key="error_{{ $attributes->whereStartsWith('wire:model')->first() }}"
        class="mt-2 text-sm text-red-600"
        id="{{ $attributes->whereStartsWith('wire:model')->first() }}-error"
    >
        {{ $message }}
    </p>
    @enderror
</div>
