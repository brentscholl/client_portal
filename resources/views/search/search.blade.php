<div id="search">
    <div class="w-full flex md:ml-0">
        <button
            v-on:click="openSearch"
            type="button"
            class="ml-3 relative p-1 text-gray-400 rounded-full hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:shadow-outline focus:text-gray-500"
            aria-label="Notifications"
        >
            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"></path>
            </svg>
        </button>

    </div>
    <div v-cloak v-show="open" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-start justify-center min-h-screen pt-6 px-4 pb-20 text-center sm:block sm:p-0">
            <transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
            <div
                v-if="open"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-5"
                aria-hidden="true"
                v-on:click="open = false"
            >
            </div>
            </transition>

            <!-- This element is to trick the browser into centering the modal contents. -->
            {{--            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>--}}

            <transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
            <div
                id="search-form"
                v-if="open"
                class="pb-4 pt-2 px-4 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <!-- In a default Laravel app, Vue will render inside an #app div -->
                <ais-instant-search
                    :search-client="searchClient"
                    index-name="{{ (new App\Models\Client)->searchableAs() }}"
                >
                    <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"></path>
                            </svg>
                        </div>
                        <ais-search-box placeholder="Search"
                            class="block w-full h-full pl-8 pr-3 py-2 rounded-md text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 sm:text-sm"
                        >
                            <template v-slot:submit-icon></template>
                            <template v-slot:reset-icon>
                                <svg class="h-3 w-3 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </template>
                        </ais-search-box>
                    </div>

                    <div class="mt-4">
                        <ais-hits>
                            <template v-slot:item="{ item }">
                                <a v-bind:href="'/admin/clients/'+ item.id" class="flex space-x-2 justify-between items-center font-medium mb-4 inline-block rounded-md px-3 py-1 bg-amber-100 text-amber-500 hover:text-white hover:bg-amber-500 transition-all duration-100 ease-in-out">
                                    <span class="flex space-x-2 items-center">
                                        <x-svg.client class="h-4 w-4"/>
                                        <span>@{{ item.title }}</span>
                                    </span>
                                    <span class="text-xs">
                                        Client
                                    </span>
                                </a>
                            </template>
                        </ais-hits>
                    </div>
                </ais-instant-search>
            </div>
            </transition>
        </div>

        <!-- See footer.blade.php for script controlling this vue component -->
    </div>
</div>

