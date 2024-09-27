<div class="relative flex items-start space-x-3"
    id="comment-{{ $action->id }}"
{{--    style="margin-bottom: -1.75rem;"--}}
>
    <!-- comment user image -->
    <div class="relative">
        <img class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white"
            src="{{ $action->user->avatarUrl() }}" alt="{{ $action->user->full_name }}">
        <span class="absolute bg-white rounded-tl px-0.5 py-px" style="bottom:-0.125rem; right: -0.25rem">
                <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: solid/chat-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path>
                </svg>
              </span>
    </div>

    <!-- comment -->
    <div class="relative min-w-0 flex-1 group hover:bg-gray-50 rounded-md px-2 h-auto transition-all duration-100 ease-in-out" style="left: -0.5rem;">
        <!-- name -->
        <div>
            <div class="text-sm">
                <a tooltip="View Comment Author" href="{{ route('admin.users.show', $action->user->id) }}" class="font-medium text-gray-900">{{ $action->user->full_name }}</a>
            </div>
            <p class="mt-0.5 text-xs text-gray-500">
                    <span class="group-hover:hidden">Commented
                    <!-- space -->
                        {{ $action->created_at->diffForHumans() }}</span>
                <span class="text-gray-700 font-medium hidden group-hover:block">Commented
                <!-- space -->
                    {{ $action->created_at->format('M d, Y @ g:ia') }}</span>
            </p>
        </div>

        <!-- body -->
        <div class="mt-2 text-sm text-gray-700 comment-body">
            {!! $action->body !!}
        </div>

        <!-- reactions -->
        <div class="flex space-x-2 items-center">
            @foreach($reactions as $type => $reaction)
                <?php
                switch ( $type ) {
                    case('thumbs_up'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f44d.svg);">&nbsp;</span>';
                        break;
                    case('smile'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f601.svg);">&nbsp;</span>';
                        break;
                    case('heart_eyes'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f60d.svg);">&nbsp;</span>';
                        break;
                    case('laugh'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f602.svg);">&nbsp;</span>';
                        break;
                    case('heart'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/2764.svg);">&nbsp;</span>';
                        break;
                    case('hands_up'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f64c.svg);">&nbsp;</span>';
                        break;
                    case('check'):
                        $emoji = '<span class="w-4 h-4 fr-emoticon fr-deletable fr-emoticon-img" style="background: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/2611.svg);">&nbsp;</span>';
                        break;
                }
                ?>
                <div class="relative reaction-container">
                    <div class="reaction-list absolute bg-white z-10 w-36 rounded-md border shadow p-2 text-xs space-y-3 items-center text-center text-gray-500 transition delay-200"
                        style="bottom: 1.75rem; left:50%; transform: translateX(-50%)">
                        {!! $emoji !!}
                        <div>
                            <?php
                            $isAuth = false;
                            $reaction_array = [];
                            $i = 0;
                            $auth_user_id = auth()->user()->id;
                            if ( $reaction instanceof Illuminate\Support\Collection ) {
                                $reaction_count = $reaction->count();
                            } else {
                                $reaction_count = count($reaction);
                            }
                            foreach ( $reaction as $r ) {
                                if ( $r instanceof Illuminate\Support\Collection ) {
                                    $user = $r->user->toArray();
                                    $r = $r->toArray();
                                } else {
                                    $user = $r['user'];
                                }
                                $you = $r['user_id'] == $auth_user_id ? ($i > 0 ? 'you' : 'You') : false;
                                if ( $r['user_id'] == $auth_user_id ) {
                                    $isAuth = true;
                                }
                                $reaction_array[$you ? $you : $user['first_name'] . ' ' . $user['last_name']] = true;
                                $i++;
                            }
                            echo formatListForSentence($reaction_array) . ' <span class="text-gray-400">reacted with ' . str_replace('_', ' ', $type) . '</span>';
                            ?>
                        </div>
                    </div>

                    <button type="button"
                        @if($isAuth)
                        wire:click="unreact('{{ $type }}')"
                        @else
                        wire:click="react('{{ $type }}')"
                        @endif
                        class="reaction-btn flex space-x-2 items-center {{ $isAuth ? 'bg-secondary-500 text-white' : 'bg-white text-gray-500' }} border border-2 py-0.5 px-2 rounded-full text-xs font-bold  hover:border-secondary-600 hover:text-white hover:bg-secondary-500 transition duration-100 ease-in-out"
                    >
                        {!! $emoji !!}
                        <span>{{ $reaction_count }}</span>
                    </button>
                </div>
        @endforeach

        <!-- add reaction button -->
            <div x-data="{ showEmojiSelection: false }" @close-slideout.window="showEmojiSelection = false" @keydown.escape="showEmojiSelection = false" @click.away="showEmojiSelection = false"
                class="h-6 min-w-6 relative">
                <!-- The reaction select box (hidden) -->
                <div x-show="showEmojiSelection"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"

                    class="flex space-x-2 p-3 shadow rounded-md border absolute bg-white" style="top:-3.5rem; left: 50%; transform:translateX(-50%)">
                    <button type="button"
                        wire:click="{{ in_array('thumbs_up', $auth_reaction_types) ? 'unreact' : 'react' }}('thumbs_up')"
                        class="{{ in_array('thumbs_up', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img " style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f44d.svg);">
                        &nbsp;
                    </button>
                    <button type="button"
                        wire:click="{{ in_array('smile', $auth_reaction_types) ? 'unreact' : 'react' }}('smile')"
                        class="{{ in_array('smile', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f601.svg);">
                        &nbsp;
                    </button>
                    <button type="button"
                        wire:click="{{ in_array('heart_eyes', $auth_reaction_types) ? 'unreact' : 'react' }}('heart_eyes')"
                        class="{{ in_array('heart_eyes', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f60d.svg);">
                        &nbsp;
                    </button>
                    <button type="button"
                        wire:click="{{ in_array('laugh', $auth_reaction_types) ? 'unreact' : 'react' }}('laugh')"
                        class="{{ in_array('laugh', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f602.svg);">
                        &nbsp;
                    </button>
                    <button type="button"
                        wire:click="{{ in_array('heart', $auth_reaction_types) ? 'unreact' : 'react' }}('heart')"
                        class="{{ in_array('heart', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/2764.svg);">
                        &nbsp;
                    </button>
                    <button type="button"
                        wire:click="{{ in_array('hands_up', $auth_reaction_types) ? 'unreact' : 'react' }}('hands_up')"
                        class="{{ in_array('hands_up', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/1f64c.svg);">
                        &nbsp;
                    </button>
                    <button type="button"
                        wire:click="{{ in_array('check', $auth_reaction_types) ? 'unreact' : 'react' }}('check')"
                        class="{{ in_array('check', $auth_reaction_types) ? 'opacity-30' : '' }} fr-emoticon fr-deletable fr-emoticon-img" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/emojione/2.0.1/assets/svg/2611.svg);">
                        &nbsp;
                    </button>
                </div>

                <!-- reaction select button that shows reaction box -->
                <button @click="showEmojiSelection = true" type="button"
                    tooltip="React to Comment"
                    class="invisible opacity-0 group-hover:visible group-hover:opacity-100 flex bg-transparent border-transparent flex-col justify-center items-center rounded-full h-6 w-6 rounded-full text-gray-400 hover:bg-gray-200 transition duration-200 ease-in-out">
                    <x-svg.reactions.react class="h-4 w-4 opacity-60"/>
                </button>
            </div>
            @if($show_reply)
                <a tooltip="Reply to Comment" href="{{ route('admin.'. strtolower(str_replace('App\Models\\', '', $action->actionable_type)) .'s.show', [$action->actionable_id]) }}"
                    class="invisible opacity-0 group-hover:visible group-hover:opacity-100 flex bg-transparent border-transparent flex-col justify-center items-center rounded-full h-6 w-6 rounded-full text-gray-400 hover:bg-gray-200 transition duration-200 ease-in-out">
                    <x-svg.reply class="h-4 w-4 opacity-60"/>
                </a>
            @endif
        </div>

        @if($action->user_id == auth()->user()->id)
            <x-menu.three-dot>
                <div>
                    @if($showMenuContent)
                        <x-modal
                            wire:click="saveComment"
                            triggerClass="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-secondary-100 hover:text-secondary-900"
                            modalTitle="Edit Comment"
                            triggerText="Edit Comment"
                            type="add"
                            submitText="Update Comment"
                            size="large"
                        >
                            <div>
                                <div>
                                    <div class="form-input-container">
                                        <div wire:ignore class="w-full">
                                            <textarea rows="3"
                                                id="comment"
                                                wire:model.defer="comment"
                                                placeholder="Leave a comment"
                                                @error('comment')
                                                class="form-input-container__input form-input-container__input--has-error"
                                                aria-invalid="true"
                                                aria-describedby="comment-error"
                                                @else
                                                class="form-input-container__input"
                                                @endif
                                            ></textarea>
                                        </div>
                                        @error('comment')
                                        <div wire:key="error_svg_comment"
                                            class="form-input-container__input__icon--has-error">
                                            <x-svg.error/>
                                        </div>
                                        @enderror
                                    </div>
                                    @error('comment')
                                    <p wire:key="error_comment"
                                        class="form-input-container__input__error-message"
                                        id="error-comment">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                            <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css"/>
                            <script type="text/javascript" src="{{ asset('js/tribute.js') }}"></script>
                            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
                            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/plugins/char_counter.min.js"></script>
                            <script>

                                var tribute = new Tribute({
                                    values: [
                                            @foreach($mentionableUsers as $user)
                                        {
                                            key: '{{ $user->full_name }}', value: '{{ $user->id }}'
                                        },
                                        @endforeach
                                    ],
                                    selectTemplate: function (item) {
                                        return '<a href="/admin/users/' + item.original.value + '" data-userid="' + item.original.value + '" class="font-bold text-secondary-500 py-0.5 px-2 bg-gray-100 rounded-md hover:bg-secondary-100">@' + item.original.key + '</a>';
                                    },
                                    // class added in the flyout menu for active item
                                    selectClass: 'mention-highlight',

                                    // class added to the menu container
                                    containerClass: 'mention-container',

                                    // class added to each list item
                                    itemClass: 'mention-item',
                                    // specify the minimum number of characters that must be typed before menu appears
                                    menuShowMinLength: 1
                                });
                                new FroalaEditor('#comment', {
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
                                        ['undo', 'redo']
                                    ],
                                    events: {
                                        initialized: function () {
                                            var editor = this;

                                            tribute.attach(editor.el);

                                            editor.events.on('keydown', function (e) {
                                                if (e.which == FroalaEditor.KEYCODE.ENTER && tribute.isActive) {
                                                    return false;
                                                }
                                            }, true);
                                        },
                                        contentChanged: function () {
                                            @this.
                                            set('comment', this.html.get());
                                        }
                                    }
                                });

                            </script>
                        </x-modal>
                        <x-modal
                            wire:click="removeComment"
                            triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                            modalTitle="Delete Comment"
                            triggerText="Delete Comment"
                        >
                            Are you sure you want to delete this comment?<br>
                            <div class="mt-3 bg-gray-100 p-2 rounded-md">
                                {!! $action->body !!}
                            </div>
                        </x-modal>
                    @else
                        <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
                    @endif
                </div>
            </x-menu.three-dot>
        @endif
    </div>
</div>
