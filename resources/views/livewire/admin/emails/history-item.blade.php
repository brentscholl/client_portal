<li>
    <div class="relative pb-6">

        @if(!$loop_last)
            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
        @endif

        <div class="group flex space-between space-x-2 rounded-md">
            <div class="flex-grow-1 w-full">
                <div class="relative flex items-start space-x-3">
                    <div>
                        <div class="relative px-1">
                            <span class="relative w-8 h-8 rounded-full ring-8 ring-gray-50 flex items-center justify-center flex items-center text-center text-secondary-500 bg-secondary-200">
                                <x-svg.send class="h-4 w-4"/>
                            </span>
                        </div>
                    </div>
                    <div class="w-full flex space-x-3 justify-between">
                        <div class="text-sm text-gray-500">
                            @if(! $email_template)
                                <span class="mb-2"><span class="text-gray-900 font-medium">{{ $email->subject }}</span> sent on</span>
                            @else
                                <span class="mb-2">Email sent on</span>
                        @endif
                        <!-- space -->
                            <span class="text-sm whitespace-nowrap text-gray-600">{{ $email->created_at->format('M d, Y @ h:i A') }}</span>
                            <!-- space -->
                            <span class="text-xs whitespace-nowrap">{{ $email->created_at->diffForHumans() }}</span><br>
                            <div class="inline-flex flex-wrap mt-2">
                                <span class="mr-3 mt-0.5 text-xs">Sent to:</span>
                                @foreach(json_decode($email->recipients) as $rep)
                                    <div class="text-xs mr-3 mb-2 py-0.5 px-1.5 rounded-full bg-gray-100 text-gray-500">{{ $rep }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div x-data="{ open: false }"
                            @close-slideout.window="open = false"
                            @keydown.window.escape="open = false;"
                            x-ref="dialog"
                        >
                            <button
                                type="button"
                                class="flex space-x-2 items-center text-xs w-full text-left px-1 py-1 rounded-md text-gray-500 hover:bg-secondary-100 hover:text-secondary-500"
                                @click="open = true"
                                wire:click="openSlideout"
                                tooltip="View the email sent"
                                tooltip-p="left"
                            >
                                <x-svg.eye class="h-5 w-5"/>
                            </button>
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
                                        <div class="flex-1 justify-center relative z-0 flex overflow-hidden">
                                            <main class="flex-1 max-w-6xl relative z-0 overflow-y-auto focus:outline-none">
                                                <!-- Start main area-->
                                                <div class="absolute inset-0 py-4 px-2">
                                                    <div class="h-full bg-gray-50 rounded-lg overflow-hidden">
                                                        <button type="button" @click="open = false;" class="absolute shadow top-2 right-0 bg-white rounded-full p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-white">
                                                            <span class="sr-only">Close panel</span>
                                                            <svg class="h-4 w-4" x-description="Heroicon name: x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>
