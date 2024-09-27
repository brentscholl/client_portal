@props([
    'label' => "",
    'required' => false,
    'placeholder' => "",
    'model' => $attributes->whereStartsWith('wire:model')->first(),
    'index' => ""
])
<div class="{{ $attributes->get('class') }}">
    <div wire:ignore
        wire:key="froala_{{ $attributes->whereStartsWith('wire:model')->first() }}"
        x-data=""
        x-init="
            new FroalaEditor($refs.editor, {
                editorClass: 'w-full rounded-lg border',
                charCounterCount: true,
                charCounterMax: 600,
                heightMin: 100,
                heightMax: 200,
                quickInsertTags: null,
                toolbarButtons: [
                    ['bold', 'italic', 'underline', 'strikeThrough'],
                    ['formatOL', 'formatUL'],
                    ['insertLink', 'emoticons'],
                    // ['undo', 'redo']
                ],
                events: {
                    initialized: function () {
                        var editor = this;

                        // tribute.attach(editor.el);

                        editor.events.on('keydown', function (e) {
                            if (e.which == FroalaEditor.KEYCODE.ENTER && tribute.isActive) {
                                return false;
                            }
                        }, true);
                    },
                    contentChanged: function () {
                        @this.editorValue('{{ $index }}', this.html.get());
                    }
                }
            });
        "
        class="form-input-container">
        <div wire class="w-full">
            <textarea
                x-ref="editor"
                rows="3"
                id="{{ $attributes->get('id') }}"
                {{ $attributes->whereStartsWith('wire:model') }}
                placeholder="{{ $placeholder }}"
                @error($attributes->whereStartsWith('wire:model')->first())
                class="form-input-container__input form-input-container__input--has-error"
                aria-invalid="true"
                aria-describedby="{{ $attributes->whereStartsWith('wire:model')->first() }}-error"
                @else
                class="form-input-container__input"
                @endif
            ></textarea>
        </div>
        @error($attributes->whereStartsWith('wire:model')->first())
        <div wire:key="error_svg_{{ $attributes->whereStartsWith('wire:model')->first() }}"
            class="form-input-container__input__icon--has-error">
            <x-svg.error/>
        </div>
        @enderror
    </div>
    @error($attributes->whereStartsWith('wire:model')->first())
    <p wire:key="error_{{ $attributes->whereStartsWith('wire:model')->first() }}"
        class="form-input-container__input__error-message"
        id="error-{{ $attributes->whereStartsWith('wire:model')->first() }}">
        {{ $message }}
    </p>
    @enderror
</div>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css"/>
    @endpush
@endonce

@once
    @push('scripts')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/plugins/char_counter.min.js"></script>
    @endpush
@endonce
