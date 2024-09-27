<div x-data="{ open: false }"
    @close-slideout.window="open = false"
    @keydown.window.escape="open = false;"
    x-ref="dialog"
>
    @if($is_editing)
        <button
            type="button"
            class="flex space-x-2 items-center text-xs w-full text-left px-1 py-1 rounded-md text-gray-500 hover:bg-secondary-100 hover:text-secondary-500"
            @click="open = true"
            wire:click="openSlideout"
            tooltip="Edit Email Template"
        >
            <x-svg.edit class="h-5 w-5"/>
        </button>
    @else
        <x-button
            type="button"
            btn="{{ $button_type }}"
            @click="open = true"
            wire:click="openSlideout"
            tooltip="Create a new Email Template"
            tooltip-p="left"
        >
            <x-svg.plus-circle class="h-5 w-5 text-white"/>
        </x-button>
    @endif
    <div x-cloak x-show="open" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-description="Background overlay, show/hide based on modal state."
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-5"
                @click="open = false"
                aria-hidden="true"
            >
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            {{--            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>--}}
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-description="Modal panel, show/hide based on modal state."
                class="flex flex-col min-w-0 flex-1 overflow-hidden relative"
                style="width: calc(100vw - 4rem); height: calc(100vh - 4rem); margin: 2rem;"
            >
                <div class="flex-1 relative z-0 flex overflow-hidden">
                    <!--**** BUILDER ****-->
                    <aside class="hidden relative xl:flex xl:flex-col flex-shrink-0 w-full max-w-3xl overflow-y-auto">
                        <!-- Start secondary column (hidden on smaller screens) -->
                        <div class="absolute inset-0 py-4 px-2">
                            <form wire:submit.prevent="sendEmail" class="h-full bg-white rounded-lg overflow-hidden flex flex-col">
                                <div class="flex flex-col space-y-6 flex-grow-1 h-full overflow-y-auto p-6 text-left">
                                    <div class="flex items-center">
                                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-secondary-100 sm:mx-0 sm:h-10 sm:w-10">
                                            <x-svg.email class="h-6 w-6 text-secondary-600"/>
                                        </div>
                                        <div class="flex-grow text-left ml-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Send Email
                                            </h3>
                                            <p class="text-xs text-gray-500 font-medium">
                                                {{ $client->title }}
                                            </p>
                                        </div>
                                    </div>
                                @include('errors.list')
                                <!-- CONTENT -->
                                    @if($slideout_open)

                                        @include('email-builder.recipients')

                                        <div>
                                            <x-input.text
                                                wire:model="subject"
                                                label="Subject"
                                            />
                                        </div>
                                        <div>
                                            <div class="relative">
                                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                                    <div class="w-full border-t border-gray-300"></div>
                                                </div>
                                                <div class="relative flex justify-start">
                                                    <span class="pr-2 bg-white text-sm text-gray-500">
                                                        Message
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @php($i = 0)
                                        @if($layout)
                                        @foreach($layout as $item)
                                            <div class="flex relative space-x-2 py-2 pr-2 rounded-md bg-gray-50 transition-all shadow-md">
                                                <button
                                                    type="button"
                                                    title="Remove"
                                                    wire:click="removeComponent({{ $i }})"
                                                    class="absolute h-4 w-4 p-0.5 rounded-full bg-red-500 text-white opacity-50 hover:opacity-100 focus:outline-none"
                                                    style="top: -0.1rem; right: -0.3rem;">
                                                    <x-svg.x/>
                                                </button>
                                                <div class="flex flex-col justify-between space-y-2 h-full">
                                                    @if($i == 0)
                                                        <div></div>
                                                    @else
                                                        <button type="button" wire:click="moveComponent('up', {{ $i }})" class="flex items-center rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-200 s-transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                                                            </svg>
                                                        </button>
                                                    @endif

                                                    @if($i == (count($layout) - 1))
                                                        <div></div>
                                                    @else
                                                        <button type="button" wire:click="moveComponent('down', {{ $i }})" class="flex items-center rounded-full p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-200 s-transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- =================================================================== -->
                                                <!-- ************************ LAYOUTS ********************************** -->
                                                <!-- =================================================================== -->
                                                <div class="flex-grow-1 w-full">
                                                    <!-- Utilities -->
                                                    @if($item['layout'] == 'message')
                                                        <div>
                                                            <div class="flex space-x-1 text-sm font-medium text-gray-500">
                                                                <x-svg.comment class="h-5 w-5"/>
                                                                <span>Message</span>
                                                            </div>
                                                            <x-input.froala-editor
                                                                wire:model="layout.{{$i}}.inputs.message"
                                                                id="layout-{{$i}}-inputs-message"
                                                                :index="$i"
                                                                placeholder="Write Message"
                                                            />
                                                        </div>
                                                    @endif

                                                    @if($item['layout'] == 'alert')
                                                        <div>
                                                            <div class="flex space-x-1 text-sm font-medium text-gray-500">
                                                                <x-svg.alert class="h-5 w-5"/>
                                                                <span>Alert</span>
                                                            </div>
                                                            <x-input.text
                                                                wire:model="layout.{{$i}}.inputs.message"
                                                            />
                                                        </div>
                                                    @endif

                                                    @if($item['layout'] == 'link_to_dashboard')
                                                        <div>
                                                            <div class="group inline-flex space-x-1 justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white gradient-secondary">
                                                                <x-svg.login class="h-5 w-5"/>
                                                                <span>Link to Dashboard</span>
                                                            </div>
                                                        </div>
                                                @endif

                                                <!-- Lists -->
                                                @if($item['layout'] == 'tasks')
                                                    @include('email-builder.tasks', ['layout' => $layout, 'i' => $i])
                                                @endif

                                                @if($item['layout'] == 'projects')
                                                    @include('email-builder.projects', ['layout' =>  $layout, 'i' => $i])
                                                @endif

                                                @if($item['layout'] == 'questions')
                                                    @include('email-builder.questions', ['layout' =>  $layout, 'i' => $i])
                                                @endif

                                                @if($item['layout'] == 'tutorials')
                                                    @include('email-builder.tutorials', ['layout' =>  $layout, 'i' => $i])
                                                @endif


                                                <!-- Singles -->
                                                @if($item['layout'] == 'single_task')
                                                    @include('email-builder.single-task', ['layout' =>  $layout, 'i' => $i])
                                                @endif

                                                @if($item['layout'] == 'single_project')
                                                    @include('email-builder.single-project', ['layout' =>  $layout, 'i' => $i])
                                                @endif

                                                @if($item['layout'] == 'single_question')
                                                    @include('email-builder.single-question', ['layout' =>  $layout, 'i' => $i])
                                                @endif

                                                @if($item['layout'] == 'single_tutorial')
                                                    @include('email-builder.single-tutorial', ['layout' =>  $layout, 'i' => $i])
                                                @endif

                                                <!---- LAYOUTS END ---->
                                                </div>
                                            </div>
                                            @php($i++)
                                        @endforeach
                                        @else
                                            <p class="flex justify-center items-center space-x-2 text-sm text-center text-gray-400 p-3 rounded-md border-dashed border"><x-svg.alert class="h-4 w-4"/><span>No components in message. Please add a component.</span></p>
                                        @endif

                                        <!-- Layout Button -->
                                        @include('email-builder.add-layout-button', ['i' => $i])

                                        <!-- Email Settings -->
                                        <div class="pt-12">
                                            <div class="relative">
                                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                                    <div class="w-full border-t border-gray-300"></div>
                                                </div>
                                                <div class="relative flex justify-start">
                                                    <span class="pr-2 bg-white text-sm text-gray-500">
                                                      Email Settings
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <x-input.radio-set label="Email Signature">
                                                <x-input.radio
                                                    wire:model.lazy="email_signature"
                                                    value="user"
                                                    label="Myself"
                                                    :first="true"
                                                />
                                                <x-input.radio
                                                    wire:model.lazy="email_signature"
                                                    value="company"
                                                    label="Company"
                                                    :last="true"
                                                />
                                            </x-input.radio-set>
                                        </div>

                                        <div>
                                            @include('email-builder.email-schedule')
                                        </div>

                                    @else
                                        <x-skeleton.input.label/>
                                        <x-skeleton.input.text/>
                                        <x-skeleton.input.textarea/>
                                @endif
                                <!-- CONTENT END -->
                                </div>
                                <div class="bg-gray-50 rounded-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">

                                    <button
                                        @click="saving = true"
                                        type="submit"
                                        class="w-full inline-flex space-x-2 items-center justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-secondary-600 text-base font-medium text-white hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:ml-3 sm:w-auto sm:text-sm"
                                    >
                                        @if($email_template)
                                            @if($schedule_email_to_send)
                                                @if($email_schedule['set_send_date'])
                                                    <span class="flex space-x-1">
                                                        <x-svg.clock class="h-4 w-4"/>
                                                        <x-svg.save class="h-4 w-4"/>
                                                    </span>
                                                    <span>Save</span>
                                                @else
                                                    <span class="flex space-x-1">
                                                        <x-svg.clock class="h-4 w-4"/>
                                                        <x-svg.send class="h-4 w-4"/>
                                                    </span>
                                                    <span>Save & Send</span>
                                                @endif
                                            @else
                                                <x-svg.send class="h-4 w-4"/>
                                                <span>Save & Send</span>
                                            @endif
                                        @else
                                            @if($schedule_email_to_send)
                                                <span class="flex space-x-1">
                                                    <x-svg.clock class="h-4 w-4"/>
                                                    <x-svg.send class="h-4 w-4"/>
                                                </span>
                                                <span>Start Schedule</span>
                                            @else
                                                <x-svg.send class="h-4 w-4"/>
                                                <span>Send</span>
                                            @endif
                                        @endif
                                    </button>
                                    @if(($email_template && !$schedule_email_to_send) || ($email_template && $schedule_email_to_send && !$email_schedule['set_send_date']) || !$email_template)
                                    <button type="button"
                                        wire:click="saveDraft"
                                        class="mt-3 w-full inline-flex space-x-2 items-center justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-secondary-500 hover:bg-secondary-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    >
                                        @if($email_template)
                                            @if($saved)
                                                <x-svg.check class="h-4 w-4 text-green-500"/>
                                                <span class="text-green-500">Save</span>
                                            @else
                                                @if($saving_as_draft)
                                                    <x-svg.spinner class="h-4 w-4"/>
                                                @else
                                                    <x-svg.save class="h-4 w-4"/>
                                                @endif
                                                <span>Save</span>
                                            @endif
                                        @else
                                            @if($saved)
                                                <x-svg.check class="h-4 w-4 text-green-500"/>
                                                <span class="text-green-500">Saved</span>
                                            @else
                                                @if($saving_as_draft)
                                                    <x-svg.spinner class="h-4 w-4"/>
                                                @else
                                                    <x-svg.save class="h-4 w-4"/>
                                                @endif
                                                <span>Save as draft</span>
                                            @endif
                                        @endif
                                    </button>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="cancel"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                    >
                                        @if($email_template)
                                            Close
                                        @else
                                            Cancel
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- End secondary column -->
                    </aside>

                    <!--**** PREVIEW ****-->
                    <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none">
                        <!-- Start main area-->
                        <div class="absolute inset-0 py-4 px-2">
                            <div class="h-full bg-gray-50 rounded-lg overflow-hidden">
                                <div class="h-full overflow-y-auto">
                                    @if($slideout_open)
                                        <div class="email-preview">
                                            <div class="email-template bg-gray-50 table mx-auto">
                                                <?php
                                                $markdown = new \Illuminate\Mail\Markdown(view(), config('mail.markdown'));
                                                echo $markdown->render("emails.notify-client", [
                                                    'data'   => $data,
                                                    'layout' => $layout
                                                ], false);
                                                ?>
                                            </div>
                                        </div>
                                    @else
                                        <div class="h-full w-full flex justify-center items-center">
                                            <x-svg.spinner class="h-10 w-10 text-secondary-500"/>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- End main area -->
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts.footer')
    <script>

        {{--function initEditor() {--}}
        {{--    return new FroalaEditor('#message', {--}}
        {{--        editorClass: 'w-full rounded-lg border',--}}
        {{--        charCounterCount: true,--}}
        {{--        charCounterMax: 600,--}}
        {{--        heightMin: 100,--}}
        {{--        heightMax: 200,--}}
        {{--        quickInsertTags: null,--}}
        {{--        toolbarButtons: [--}}
        {{--            ['bold', 'italic', 'underline', 'strikeThrough'],--}}
        {{--            ['formatOL', 'formatUL'],--}}
        {{--            ['insertLink', 'emoticons'],--}}
        {{--            // ['undo', 'redo']--}}
        {{--        ],--}}
        {{--        events: {--}}
        {{--            initialized: function () {--}}
        {{--                var editor = this;--}}

        {{--                // tribute.attach(editor.el);--}}

        {{--                editor.events.on('keydown', function (e) {--}}
        {{--                    if (e.which == FroalaEditor.KEYCODE.ENTER && tribute.isActive) {--}}
        {{--                        return false;--}}
        {{--                    }--}}
        {{--                }, true);--}}
        {{--            },--}}
        {{--            contentChanged: function () {--}}
        {{--                @this.--}}
        {{--                set('message', this.html.get());--}}
        {{--            }--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}

        {{--var editor = initEditor();--}}
        {{--Livewire.on('messageCreated', $event => {--}}
        {{--    editor.destroy();--}}
        {{--    document.getElementById("message").value = "";--}}
        {{--    editor = initEditor();--}}
        {{--});--}}

        {{--window.addEventListener('layoutOrderChanged', event => {--}}
        {{--    initEditor();--}}
        {{--    console.log('here');--}}
        {{--});--}}
    </script>
@endsection
