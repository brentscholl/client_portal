<section class="mt-8 xl:mt-10">
    <x-card :opened="false">
        <x-card.header title="Clients" count="{{ $clients->count() }}">
{{--            @livewire('admin.clients.assign', ['model' => $model], key('assign-client'))--}}
        </x-card.header>
        <x-card.body>
            <div class="grid grid-cols-4 gap-4">
                @forelse($clients as $client)
                    <a tooltip="View Client" href="{{ route('admin.clients.show', $client->id) }}" class="group col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200 transition ease-in-out duration-150 border border-white hover:shadow-lg hover:border-secondary-500">
                        <div class="flex flex-col p-8">
                            <img
                                class="w-26 h-26 flex-shrink-0 mx-auto rounded-full"
                                src="{{ $client->avatarUrl() }}"
                                alt=""
                            >
                            <h3 class="mt-6 text-gray-900 text-md font-bold group-hover:text-secondary-500 transition ease-in-out duration-150">{{ $client->title }}</h3>
                        </div>
                    </a>
                @empty
                    <span class="relative block w-full border-2 border-gray-200 border-dashed rounded-lg p-12 text-center">
                        <x-svg.client class="mx-auto h-12 w-12 text-gray-200"/>
                        <span class="mt-2 block text-sm font-medium text-gray-300">
                          No clients assigned
                        </span>
                    </span>
                @endforelse
            </div>
        </x-card.body>
    </x-card>
</section>
