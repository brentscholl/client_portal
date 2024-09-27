<div x-data="{ 'showComponents': false }" class="relative" @keydown.escape="showComponents = false" @click.away="showComponents = false">
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t border-gray-300 border-dashed"></div>
    </div>
    <div class="relative flex justify-center">
        <div
            x-cloak
            x-show="showComponents"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="z-10 absolute w-full min-w-xl flex-col space-y-3 items-center justify-center rounded-md bg-gray-50 shadow-md p-3 border" style="bottom: 2.75rem;">
            <button type="button" @click="showComponents = false" class="absolute top-1 right-1 p-1 text-gray-500 rounded-full hover:bg-white hover:text-gray-900 s-transition">
                <x-svg.x class="h-4 w-4"/>
            </button>
            <div class="flex justify-center items-center space-x-3">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-secondary-100 sm:mx-0 sm:h-10 sm:w-10">
                    <x-svg.plus-circle class="h-6 w-6 text-secondary-600"/>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Add a Component</h3>
            </div>
            <div class="flex space-x-3">
                <div class="flex flex-col flex-grow-1 space-y-2 w-1/3">
                    <p class="text-center px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilities</p>
                    <button
                        wire:click="addComponent('message', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.comment class="h-4 w-4"/>
                        <span>Message</span>
                    </button>
                    <button
                        wire:click="addComponent('alert', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.alert class="h-4 w-4"/>
                        <span>Alert</span>
                    </button>
                    <button
                        wire:click="addComponent('link_to_dashboard', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.login class="h-4 w-4"/>
                        <span>Link to Dashboard</span>
                    </button>
                </div>
                <div class="flex flex-col flex-grow-1 space-y-2 w-1/3">
                    <p class="text-center px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lists</p>
                    <button
                        wire:click="addComponent('tasks', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.task class="h-4 w-4"/>
                        <span>Tasks</span>
                    </button>
                    <button
                        wire:click="addComponent('projects', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.project class="h-4 w-4"/>
                        <span>Projects</span>
                    </button>
                    <button
                        wire:click="addComponent('questions', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.question class="h-4 w-4"/>
                        <span>Questions</span>
                    </button>
                    <button
                        wire:click="addComponent('tutorials', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.tutorial class="h-4 w-4"/>
                        <span>Tutorials</span>
                    </button>
                </div>
                <div class="flex flex-col flex-grow-1 space-y-2 w-1/3">
                    <p class="text-center px-6 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Singles</p>
                    <button
                        wire:click="addComponent('single_task', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.task class="h-4 w-4"/>
                        <span>Task</span>
                    </button>
                    <button
                        wire:click="addComponent('single_project', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.project class="h-4 w-4"/>
                        <span>Project</span>
                    </button>
                    <button
                        wire:click="addComponent('single_question', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.question class="h-4 w-4"/>
                        <span>Question</span>
                    </button>
                    <button
                        wire:click="addComponent('single_tutorial', {{ $i }})"
                        type="button"
                        class="flex space-x-1 items-center justify-center w-full block px-3 py-2 rounded-md border border-secondary-500 text-center text-sm text-secondary-500 hover:bg-secondary-500 hover:text-white block s-transition"
                    >
                        <x-svg.tutorial class="h-4 w-4"/>
                        <span>Tutorial</span>
                    </button>
                </div>

            </div>

        </div>
        <button
            @click="showComponents = !showComponents"
            type="button"
            class="inline-flex items-center shadow-sm px-4 py-1.5 border border-dashed border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-secondary-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500"
        >
            <svg class="-ml-1.5 mr-1 h-5 w-5" x-description="Heroicon name: solid/plus-sm" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
            </svg>
            <span>Add Component</span>
        </button>
    </div>
</div>
