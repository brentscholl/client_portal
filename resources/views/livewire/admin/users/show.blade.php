<div>
    <x-bread-crumb.bar>
        <x-bread-crumb.link tooltip="View all Users" route="{{ route('admin.users.index') }}">Users</x-bread-crumb.link>
        <x-bread-crumb.link :current="true" route="{{ route('admin.users.show', $user->id) }}"><x-svg.user class="h-5 w-5" /><span>{{ Str::limit($user->fullname,30) }}</span></x-bread-crumb.link>
    </x-bread-crumb.bar>

    <main class="flex-1 relative focus:outline-none">
        <div class="py-8 xl:py-10">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 xl:max-w-full xl:grid xl:grid-cols-5">
                <div class="xl:col-span-4 xl:pr-8 xl:border-r xl:border-gray-200">
                    <div>
                        <div>
                            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
                                <div class="md:flex md:items-center md:justify-start md:space-x-4">
                                    <div>
                                        <img class="h-28 w-auto"
                                            src="{{$user->avatarUrl()}}"
                                            alt="{{ $user->fullname }}">
                                    </div>
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->fullname }}</h1>
                                        <div class="text-sm leading-5 text-gray-500 hover:text-gray-600 transition duration-300 ease-in-out">
                                            <a href="mailto:{{$user->email}}">{{$user->email}}</a> @if($user->phone) |
                                            <a class="text-blue-600 hover:text-blue-400 transition duration-300 ease-in-out" href="tel:{{$user->phone}}">{{ $user->phone }}</a> @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex space-x-3 md:mt-0">
                                    @livewire('admin.users.edit', ['user' => $user], key('user-edit'))
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($user->description)
                        <section class="mt-8 xl:mt-10">
                            <div class="bg-white rounded shadow-md p-5 flex">
                                <x-svg.info-circle class="-ml-1 mr-2 h-5 w-5 text-gray-400 inline"/>
                                <div>
                                    @if($user->description)
                                        <p class="text-gray-500 text-sm">{!! $user->description !!}</p>
                                    @endif
                                </div>
                            </div>
                        </section>
                    @endif
                    @livewire('admin.projects.index-card', ['user' => $user, 'showService' => false, 'cardOpened' => true])
                </div>
                @include('admin.users.user-sidebar', ['user' => $user])
            </div>
        </div>
    </main>
</div>
