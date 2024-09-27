@extends('layouts.base')

@section('body')
    <div id="app">
        <div x-data="{ sidebarOpen: false }" class="h-screen flex overflow-hidden bg-gray-50" @keydown.window.escape="sidebarOpen = false">

            @include('layouts.mobile-menu')

            @include('layouts.sidebar')

            <div class="flex-1 overflow-auto focus:outline-none" tabindex="0">
                @if(session()->has('impersonate'))
                    <div class="gradient relative">
                        <div class="absolute top-0 left-0 w-full h-full bg-secondary-300 opacity-30 animate-pulse"></div>
                        <div class="relative max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-center flex-wrap">
                                <div class="w-0 flex-1 flex items-center justify-center">
                                    <p class="font-medium text-white">
                                            <span class="md:hidden">
                                                You are impersonating <strong>{{ auth()->user()->full_name }}</strong>
                                            </span>
                                        <span class="hidden md:inline">
                                                You are impersonating <strong>{{ auth()->user()->full_name }}</strong>
                                            </span>
                                        <span class="block sm:ml-2 sm:inline-block">
                                                <a href="{{ route('leave-impersonation') }}" class="text-white font-bold underline hover:text-blue-200 transition durration-100 ease-in-out">
                                                    Leave Impersonation &rarr;
                                                </a>
                                            </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(is_null(auth()->user()->client_id))
                    @include('layouts.admin.navbar')
                @else
                    @include('layouts.client.navbar')
                @endif

                <main class="flex-1 focus:outline-none" tabindex="0" x-data="">


                    @yield('content')

                    @isset($slot)
                        {{ $slot }}
                    @endisset

                    @yield('slide-out')
                </main>
            </div>
        </div>
    </div>
@endsection
