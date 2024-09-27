<tr>
    <td>
        <div class="flex-shrink-0 h-10 w-10">
            <img class="h-10 w-10 rounded-full"
                src="{{$client->avatarUrl()}}"
                alt="">
        </div>
    </td>
    <td>
        <div class="leading-5 font-medium text-gray-900 hover:text-secondary-500"><a href="{{ route('admin.clients.show', $client->id) }}">{{ $client->title }}</a></div>
    </td>
    @if(!$simple)
        <td>
            <div class="text-gray-800"><span class="text-gray-800 font-semibold">Annual:</span> ${{ number_format($client->annual_budget, 2) }}</div>
            <div class="text-gray-500"><span class="text-gray-500 font-semibold">Monthly:</span> ${{ number_format($client->monthly_budget, 2) }}</div>
        </td>
        <td>
            <a class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ $client->website_url }}" target="_blank"  tooltip="View website">{{ $client->website_url }}</a>
        </td>
    @endif
    <td class="leading-5 text-gray-500">
        @if($client->primaryContact)
            <a class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="{{ route('admin.users.show', $client->primaryContact->id) }}"  tooltip="View user">{{ $client->primaryContact->full_name }}</a><br>
            <a class="text-xs text-gray-400 hover:text-gray-500 transition duration-300 ease-in-out" href="mailto:{{ $client->primaryContact->email }}"  tooltip="Email user">{{ $client->primaryContact->email }}</a>
        @else
            <div class="text-gray-400 text-xs">No Primary Contact Set</div>
        @endif
    </td>
    @if(!$simple)
        <td class="leading-5 text-gray-500">
            <div class="flex justify-center">
            </div>
        </td>
        <td class="text-right leading-5 font-medium">
            <x-menu.three-dot :inline="true">
                @if($showMenuContent)
                <div>
                    @livewire('admin.clients.edit', ['client' => $client, 'button_type' => 'inline-menu'], key('client-edit-' . $client->id))
                    <x-modal
                        wire:click="destroy()"
                        triggerText="Delete Client"
                        triggerClass="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 hover:text-red-900"
                        modalTitle="Delete Client"
                    >
                        Are you sure you want to delete this Client?<br><br>
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
                @else
                    <x-svg.spinner class="w-6 h-6 my-4 mx-auto text-secondary-500"/>
                @endif
            </x-menu.three-dot>
        </td>
    @endif
</tr>
