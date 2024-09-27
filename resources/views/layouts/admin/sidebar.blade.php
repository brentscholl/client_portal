<div>
    <div class="px-4">
        <img class="h-7 w-auto" src="/assets/STEALTH-logo-white.svg" alt="Stealth logo">
        <p class="text-sm font-semibold text-secondary-500 mb-0 leading-none tracking-widest mt-1 text-center uppercase">Admin</p>
    </div>
    <div class="mt-5 flex-1 flex flex-col overflow-y-auto">
        <div class="overflow-y-auto">
            <nav class="px-2 space-y-1">
                <x-desktop-sidebar-link route="home" is="/">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" x-description="Heroicon name: home" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Home
                </x-desktop-sidebar-link>

                <x-desktop-sidebar-link route="admin.clients.index" is="admin/clients*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Clients
                </x-desktop-sidebar-link>

                <x-desktop-sidebar-link route="admin.projects.index" is="admin/projects*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Projects
                </x-desktop-sidebar-link>

                <x-desktop-sidebar-link route="admin.tasks.index" is="admin/tasks*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Tasks
                </x-desktop-sidebar-link>
            </nav>
        </div>
        <hr class="h-px mt-6 bg-primary-700 border-none">
        <div class="mt-6 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">

                <x-desktop-sidebar-link route="admin.services.index" is="admin/services*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Services
                </x-desktop-sidebar-link>

                <x-desktop-sidebar-link route="admin.questions.index" is="admin/questionaires*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Questions
                </x-desktop-sidebar-link>

                <x-desktop-sidebar-link route="admin.tutorials.index" is="admin/tutorials*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    Tutorials
                </x-desktop-sidebar-link>

                <x-desktop-sidebar-link route="admin.files.index" is="admin/files*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                    </svg>
                    Files
                </x-desktop-sidebar-link>
            </nav>
        </div>
        <hr class="h-px mt-6 bg-primary-700 border-none">
        <div class="mt-6 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">
                <x-desktop-sidebar-link route="admin.teams.index" is="admin/teams*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Teams
                </x-desktop-sidebar-link>
                <x-desktop-sidebar-link route="admin.users.index" is="admin/users*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                    Users
                </x-desktop-sidebar-link>
            </nav>
        </div>
        <hr class="h-px mt-6 bg-primary-700 border-none">
        <div class="mt-6 flex-1 h-0 overflow-y-auto">
            <nav class="px-2 space-y-1">
                <x-desktop-sidebar-link route="settings.general" is="settings*">
                    <svg class="mr-4 h-6 w-6 text-secondary-500 group-hover:text-secondary-500 group-focus:text-secondary-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </x-desktop-sidebar-link>
            </nav>
        </div>
    </div>
</div>
