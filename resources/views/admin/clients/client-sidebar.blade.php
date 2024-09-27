<aside class="hidden xl:block xl:pl-8">
    <div class="space-y-5">
        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            @if($client->archived)
                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                <span class="text-green-700 text-sm font-medium">Archived</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path
                    d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"/>
            </svg>
            @if($client->projects)
                <span class="text-gray-900 text-sm font-medium">{{ $client->projects->where('status', '!=', 'canceled')->where('status', '!=', 'on-hold')->count() }} projects active</span>
            @else
                <span class="text-gray-900 text-sm font-medium">No projects created</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd"
                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"/>
            </svg>
            @if($client->tasks)
                <span class="text-gray-900 text-sm font-medium">{{ $client->tasks ? $client->tasks->where('status', 'completed')->count() : '0' }}/{{ $client->tasks ? $client->tasks->count() : '0' }} tasks completed</span>
            @else
                <span class="text-gray-900 text-sm font-medium">No tasks created</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            @if($client->annual_budget)
                <span class="text-gray-900 text-sm font-medium">${{ number_format($client->annual_budget, 2) }} Annual Budget</span>
            @else
                <span class="text-gray-900 text-sm font-medium">Annual budget not set.</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
            </svg>
            @if($client->monthly_budget)
                <span class="text-gray-900 text-sm font-medium">${{ number_format($client->monthly_budget, 2) }} Monthly Budget</span>
            @else
                <span class="text-gray-900 text-sm font-medium">Monthly budget not set.</span>
            @endif
        </div>

        <!-- Sidebar Item -->
        <div class="flex items-center space-x-2">
            <svg class="h-5 w-5 text-gray-400" x-description="Heroicon name: calendar"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="text-gray-900 text-sm font-medium">Created on <time
                    datetime="2020-12-02">{{ date_format($client->created_at, 'M d, Y @ h:ia') }}</time></span>
        </div>

        <!-- Sidebar Item -->
        <x-modal
            wire:click="destroy"
            triggerClass="flex items-center space-x-2 text-left text-sm text-red-700 hover:text-red-900 transition duration-100 ease-in-out"
            modalTitle="Delete Client"
        >
            <x-slot name="triggerText">
                <x-svg.trash class="h-5 w-5"/>
                <span class="text-sm font-medium">Delete Client</span>
            </x-slot>
            Are you sure you want to delete this Client?<br>
            <div class="rounded-md p-3 bg-gray-100 mt-4">
                <strong>What will also be deleted:</strong>
                <ul class="ml-4 mt-2 text-xs list-outside list-disc text-gray-500">
                    <li>Projects</li>
                    <li>Phases</li>
                    <li>Tasks</li>
                    <li>Answers</li>
                    <li>Files</li>
                </ul>
            </div>
        </x-modal>
    </div>
    <div class="mt-6 border-t border-gray-200 py-6 space-y-8">
        <div>
            @livewire('admin.assign-user', ['model' => $client, 'assign_marketing_advisor' => true ])
        </div>
        <div>
            @livewire('admin.assign-user', ['model' => $client, 'allow_assign' => false])
        </div>
        <div>
            @livewire('admin.assign-service', ['client' => $client])
        </div>
    </div>
</aside>
